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

import { extend, override } from 'flarum/common/extend';
import app from 'flarum/forum/app';
import CommentPost from 'flarum/forum/components/CommentPost';
import DiscussionLink from './components/DiscussionLink';
import DiscussionReferencedPost from './components/DiscussionReferencedPost';

app.initializers.add('club-1/flarum-ext-cross-references', () => {
  app.postComponents.discussionReferenced = DiscussionReferencedPost;
});

extend(CommentPost.prototype, 'content', (original) => {
  const postBody = original[1];
  const content = postBody.children[0];
  const el = document.createElement('p');
  el.innerHTML = content.children;
  el.querySelectorAll('a').forEach((a: HTMLAnchorElement) => {
    if (a.text !== a.href) {
      return;
    }
    if (a.protocol === document.location.protocol && a.host === document.location.host) {
      const match = a.pathname.match(/\/d\/([0-9]+)/);
      if (match == null) {
        return;
      }
      const discussionId = match[1];
      const discussion = app.store.getById('discussions', discussionId);
      m.mount(a, {view: () => m(DiscussionLink, {discussion, href: a.href})});
    }
  });
  content.children = el.innerHTML;
});
