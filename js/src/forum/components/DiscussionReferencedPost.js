/*
 * This file is part of club-1/flarum-ext-cross-references.
 *
 * Copyright (c) 2022 Nicolas Peugnet <nicolas@club1.fr>.
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

import app from 'flarum/forum/app';
import EventPost from 'flarum/forum/components/EventPost';
import Link from 'flarum/common/components/Link';


export default class DiscussionReferencedPost extends EventPost {
  static initAttrs(attrs) {
    super.initAttrs(attrs);

    const sourceId = attrs.post.content()[0];
    attrs.source = app.store.getById('discussions', sourceId);
  }
  icon() {
    return 'fas fa-reply';
  }

  descriptionKey() {
    return 'club-1-cross-references.forum.post_stream.discussion_referenced_text';
  }

  descriptionData() {
    return {
      source: <Link href={app.route('discussion', {id: this.attrs.source.id()})}>{this.attrs.source.title()}</Link>
    }
  }
}
