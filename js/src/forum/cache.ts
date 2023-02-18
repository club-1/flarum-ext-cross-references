/*
 * This file is part of club-1/flarum-ext-cross-references.
 *
 * Copyright (c) 2023 Nicolas Peugnet <nicolas@club1.fr>.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import Model from 'flarum/common/Model';
import Discussion from 'flarum/common/models/Discussion';
import app from 'flarum/forum/app';

export interface Cache<T> {
  get(key: string): T | undefined
  set(key: string, value: T): this
}

type FIFOCacheEntry = {key: string, next?: FIFOCacheEntry};

export class FIFOCache<T> implements Cache<T> {
  protected head?: FIFOCacheEntry;
  protected tail?: FIFOCacheEntry;
  protected data: Map<string, T> = new Map();
  protected capacity: number;

  constructor(capacity: number) {
    this.capacity = capacity;
  }

  get(key: string): T | undefined {
    return this.data.get(key);
  }

  set(key: string, value: T): this {
    if (this.data.size == this.capacity) {
      const evicted = this.head!;
      this.data.delete(evicted.key);
      this.head = evicted.next;
    }
    const entry: FIFOCacheEntry = {key}
    if (!this.head) {
      this.head = entry;
    }
    if (!this.tail) {
      this.tail = entry;
    } else {
      this.tail.next = entry;
      this.tail = entry;
    }
    this.data.set(key, value);
    return this;
  }
}

function getKey(name: string, id: string): string {
  return name + id;
}

const ModelMap: {[name: string]: string} = {}
ModelMap[Discussion.name] = 'discussions';


/** Empty function used to disable callbacks */
function noop() {};

type ResolveFunc<T> = (value: T[] | PromiseLike<T[]>) => void;
type RejectFunc = (reason?: any) => void;
const BUFFER_SIZE = 10;
const BUFFER_TIMEOUT_MS = 30;

class RequestsBuffer<T extends Model> {
  protected type: string;
  protected requests: Map<string, Promise<T | null>> = new Map;

  protected timer?: ReturnType<typeof setTimeout>;
  protected request?: Promise<T[]>;
  protected flushFunc?: () => void;

  constructor(type: string) {
    this.type = type;
  }

  public push(id: string): Promise<T | null> {
    let req = this.requests.get(id);
    if (req) {
      return req;
    }
    if (!this.request) {
      this.request = this.prepareRequest();
    }
    req = this.request.then((res) => {
      return res.find((obj) => obj.id() == id) || null
    });
    this.requests.set(id, req);
    if (this.requests.size == BUFFER_SIZE) {
      this.flushNow();
    }
    return req;
  }

  protected prepareRequest(): Promise<T[]> {
    return new Promise<T[]>((resolve, reject) => {
      this.flushFunc = () => this.flushBuffer(resolve, reject);
      this.timer = setTimeout(this.flushFunc, BUFFER_TIMEOUT_MS)
    });
  }

  protected flushNow(): void {
    clearTimeout(this.timer);
    this.flushFunc!();
  }

  protected flushBuffer(resolve: ResolveFunc<T>, reject: RejectFunc) {
    const ids = Array.from(this.requests.keys());
    this.requests = new Map;
    delete this.timer;
    delete this.request;
    delete this.flushFunc;

    app.store.find<T[]>(ModelMap[this.type], ids, {}, {errorHandler: noop})
      .catch(reject)
      .then((res) => {
        if (!res) {
          reject();
          return [];
        }
        resolve(res)
        return res;
      });
  }
};

export abstract class ResponseCache {
  private static requestsBuffers: Map<string, RequestsBuffer<Model>> = new Map;
  private static responseErrors: Cache<boolean> = new FIFOCache(128);
  private static inFlight: Map<string, Promise<Model | null>> = new Map();

  public static async find<T extends Model>(m: new () => T, id: string): Promise<T | null> {
    const key = getKey(m.name, id);

    if (this.responseErrors.get(key) == true) {
      return null;
    }
    const inFlight = this.inFlight.get(key);
    if (inFlight) {
      return inFlight as Promise<T | null>;
    }

    let buffer = this.requestsBuffers.get(m.name);
    if (!buffer) {
      buffer = new RequestsBuffer<T>(m.name);
      this.requestsBuffers.set(m.name, buffer);
    }
    const req = buffer.push(id) as Promise<T | null>
    this.inFlight.set(key, req);
    return req
      .then((res) => {
        if (!res) {
          this.responseErrors.set(key, true);
        }
        return res;
      })
      .finally(() => this.inFlight.delete(key));
  }
}
