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

function key(name: string, id: string): string {
  return name + id;
}

const ModelMap: {[name: string]: string} = {}
ModelMap[Discussion.name] = 'discussions';


/** Empty function used to disable callbacks */
function noop() {};

export abstract class ResponseCache {
  private static responseErrors: Cache<boolean> = new FIFOCache(128);

  public static async find<T extends Model>(m: new () => T, id: string, options = {}): Promise<T | null> {
    if (this.responseErrors.get(key(m.name, id)) == true) {
      return null;
    }
    const res = await app.store.find<T>(ModelMap[m.name] , id, options, {errorHandler: noop})
      .catch(noop);
    if (res) {
      return res;
    }
    this.responseErrors.set(key(m.name, id), true);
    return null;
  }
}
