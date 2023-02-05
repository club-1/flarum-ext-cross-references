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
use Flarum\Post\Event\Posted;
use Flarum\Post\Event\Revised;
use s9e\TextFormatter\Utils;

class PostEventListener
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
        $this->discussionPath = $this->urlGen->to('forum')->route('discussion', ['id' => '']);
    }

    /** Indirection to allow mocking the static call in unit tests */
    protected function findDiscussion(string $id): ?Discussion
    {
        return Discussion::find($id);
    }

    /**
     * @param Posted | Revised $event
     */
    public function handle(object $event)
    {
        $xml = $event->post->parsed_content;
        $xrefshortIds  = utils::getattributevalues($xml, 'CROSSREFERENCESHORT', 'id');
        $xrefurlIds    = utils::getattributevalues($xml, 'CROSSREFERENCEURL', 'id');
        $xrefurlcomIds = utils::getattributevalues($xml, 'CROSSREFERENCEURLCOMMENT', 'id');
        $targetIds = array_unique(array_merge($xrefshortIds, $xrefurlIds, $xrefurlcomIds));
        foreach($targetIds as $targetId) {
            if ($targetId == $event->post->discussion_id) {
                continue;
            }
            $target = $this->findDiscussion($targetId);
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

            $target->mergePost($post);
        }
    }
}
