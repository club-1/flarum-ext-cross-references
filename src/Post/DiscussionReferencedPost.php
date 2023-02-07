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

namespace Club1\CrossReferences\Post;

use Carbon\Carbon;
use Flarum\Post\AbstractEventPost;
use Flarum\Post\MergeableInterface;
use Flarum\Post\Post;

class DiscussionReferencedPost extends AbstractEventPost implements MergeableInterface
{
    /**
     * {@inheritdoc}
     */
    public static $type = 'discussionReferenced';

    /**
     * {@inheritdoc}
     */
    public function saveAfter(Post $previous = null)
    {
        // If the previous post is another 'discussion referenced' post, and it's
        // by the same user, then we can merge this post into it.
        if ($previous instanceof static && $this->user_id === $previous->user_id) {
            $previous->content = array_merge($previous->content, $this->content);
            $previous->save();
            return $previous;
        }

        $this->save();

        return $this;
    }

    /**
     * Create a new instance in reply to a discussion.
     */
    public static function reply(int $discussionId, int $userId, int $sourceId)
    {
        $post = new static;

        $post->content = [$sourceId];
        $post->created_at = Carbon::now();
        $post->discussion_id = $discussionId;
        $post->user_id = $userId;

        return $post;
    }
}
