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

import { ComponentAttrs } from 'flarum/common/Component';
import { extend } from 'flarum/common/extend';
import Discussion from 'flarum/common/models/Discussion';
import app from 'flarum/forum/app';
import CommentPost from 'flarum/forum/components/CommentPost';
import DiscussionHero from 'flarum/forum/components/DiscussionHero';
import DiscussionListItem from 'flarum/forum/components/DiscussionListItem';
import DiscussionId from './components/DiscussionId';
import DiscussionLink from './components/DiscussionLink';
import DiscussionReferencedPost from './components/DiscussionReferencedPost';

app.initializers.add('club-1-cross-references', function(app) {
  app.postComponents.discussionReferenced = DiscussionReferencedPost;
  addSourceLinkReplacement();

  addDiscussionTitleId();
  addDiscussionListId();
});

/**
 * Extend CommentPost to replace plain links to discussions into
 * formated ones using its title as the text.
 */
function addSourceLinkReplacement() {
  extend(CommentPost.prototype, 'content', function(original) {
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
}

/**
 * Extend DiscussionHero to add its ID in the title item.
 */
function addDiscussionTitleId() {
  extend(DiscussionHero.prototype, 'items', function(items) {
    if (!app.forum.attribute('showDiscussionId')) {
      return;
    }
    const attrs = this.attrs as ComponentAttrs & {discussion: Discussion}
    const discussionId = attrs.discussion.id()
    const title = items.get('title');
    items.setContent('title', m('h2', [
      title.text,
      ' ',
      m(DiscussionId, {discussionId}),
    ]));
  });
}

/**
 * Extend DiscussionListItem to add its ID as an item with a high priority.
 */
function addDiscussionListId() {
  extend(DiscussionListItem.prototype, 'infoItems', function(items) {
    if (!app.forum.attribute('showDiscussionId')) {
      return;
    }
    const attrs = this.attrs as ComponentAttrs & {discussion: Discussion}
    const discussionId = attrs.discussion.id();
    items.add('id', m(DiscussionId, {discussionId}), 90);
  });
}
