<?php

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

namespace Club1\CrossReferences\Listener;

use Club1\CrossReferences\Post\DiscussionReferencedPost;
use Flarum\Discussion\Discussion;
use Flarum\Http\UrlGenerator;
use Flarum\Post\Event\Saving;

class PostSavedListener
{

    /**
     * @var UrlGenerator
     */
    protected $urlGen;

    /**
     * @var string
     */
    protected $discussionPath;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGen = $urlGenerator;
        $this->discussionPath = $this->urlGen->to('forum')->path('d');
    }

    public function handle(Saving $event)
    {
        $matches = [];
        preg_match_all("~$this->discussionPath/([0-9]+)~", $event->post->content, $matches);
        foreach($matches[1] as $targetId) {
            if ($targetId == $event->post->discussion_id) {
                continue;
            }
            /** @var Discussion|null */
            $target = Discussion::find($targetId);
            if ($target == null) {
                continue;
            }
            if ($target->sources()->where('source_id', $event->post->discussion_id)->exists()) {
                continue;
            }
            $target->sources()->save($event->post->discussion);
            $post = DiscussionReferencedPost::reply(
                $targetId,
                $event->actor->id,
                $event->post->discussion_id
            );

            $post->save();
        }
    }
}
