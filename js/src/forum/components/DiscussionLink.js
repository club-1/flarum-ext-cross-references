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
import Link from 'flarum/common/components/Link';
import Component from 'flarum/common/Component';
import tagsLabel from 'flarum/tags/helpers/tagsLabel';
import DiscussionId from './DiscussionId';

export default class DiscussionLink extends Link {
  oninit(vnode) {
    super.oninit(vnode);
    this.discussion = app.store.getById('discussions', this.attrs.discussionId)
    if ('flarum-tags' in flarum.extensions && !this.discussion.tags()) {
      app.store.find('discussions', this.attrs.discussionId, {include: 'tags'})
        .then((discussion) => {
          this.discussion = discussion;
          m.redraw();
        });
    }
  }

  view() {
    const id = this.attrs.discussionId;
    const href = this.attrs.href;
    const showId = app.forum.attribute('showDiscussionId');
    const isComment = href && /\/d\/[^\/]+\/[0-9]+/.test(href);
    return (
      <Link
        href={href ? href : app.route('discussion', {id})}
        class="DiscussionLink"
      >
        {
          this.discussion.title()
        } {
          showId && <DiscussionId discussionId={id} />
        } {
          isComment && <DiscussionComment/>
        } {
          'flarum-tags' in flarum.extensions
            && this.discussion.tags()
            && tagsLabel(this.discussion.tags().filter((tag) => {
              return tag.position() !== null && !tag.isChild();
            }))
        }
      </Link>
    );
  }
}

class DiscussionComment extends Component {
  view() {
    return <span class="DiscussionComment">
      ({app.translator.trans('club-1-cross-references.forum.comment')})
    </span>
  }
}
