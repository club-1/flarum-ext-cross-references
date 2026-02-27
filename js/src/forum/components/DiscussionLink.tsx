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
import type { ComponentAttrs } from 'flarum/common/Component';
import DiscussionId from './DiscussionId';
import { ResponseCache } from '../cache';
import Discussion from 'flarum/common/models/Discussion';
import type { Vnode } from 'mithril';

export interface IDiscussion {
  id(): string|undefined
  title(): string
}

type DiscussionLinkAttrs = ComponentAttrs & {
  discussion?: IDiscussion,
  discussionId: string,
  href?: string,
}

export default class DiscussionLink extends Link {
  attrs!: DiscussionLinkAttrs;

  view() {
    const { discussion, discussionId, href } = this.attrs;
    const showId = app.forum.attribute('showDiscussionId');
    const isComment = href && /\/d\/[^\/]+\/[0-9]+/.test(href);

    if (discussion) {

      return (
        <Link
          href={href ? href : app.route('discussion', { id: discussionId })}
          class="DiscussionLink RouteSet"
        >
          {discussion.title()}
          &ensp;
          {showId && <DiscussionId discussionId={discussionId} />}
          {isComment && <DiscussionComment />}
        </Link>
      );
    }

    return (
      <span class="DiscussionLink DiscussionUnknown">
        {app.translator.trans('club-1-cross-references.forum.unknown_discussion')}
        &ensp;
        {showId && <DiscussionId discussionId={discussionId} />}
        {isComment && <DiscussionComment />}
      </span>
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
