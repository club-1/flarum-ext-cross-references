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

import EventPost from 'flarum/forum/components/EventPost';
import { IPostAttrs } from 'flarum/forum/components/Post';
import DiscussionLink from './DiscussionLink';

type DiscussionReferencedPostAttrs = IPostAttrs & {sourceIds: string[]};

export default class DiscussionReferencedPost extends EventPost {
  attrs!: DiscussionReferencedPostAttrs

  static initAttrs(attrs: DiscussionReferencedPostAttrs) {
    super.initAttrs(attrs);

    attrs.sourceIds = attrs.post.content() as unknown as string[];
  }
  icon() {
    return 'fas fa-reply';
  }

  descriptionKey() {
    return 'club-1-cross-references.forum.post_stream.discussion_referenced_text';
  }

  descriptionData() {
    if (this.attrs.sourceIds.length == 1) {
      return {
        source: <DiscussionLink discussionId={this.attrs.sourceIds[0]} />
      }
    } else {
      return {
        source: <ul>
          {this.attrs.sourceIds.map((id) =>
            <li><DiscussionLink discussionId={id} /></li>
          )}
        </ul>
      }
    }
  }
}
