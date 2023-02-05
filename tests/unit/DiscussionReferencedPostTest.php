<?php

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

namespace Club1\CrossReferences\Tests\unit;

use Club1\CrossReferences\Post\DiscussionReferencedPost;
use Flarum\Post\CommentPost;
use Flarum\Testing\unit\TestCase;
use Mockery as m;

use function PHPUnit\Framework\assertEquals;

class DiscussionReferencedPostTest extends TestCase
{
    public function testSaveAfterDifferentClass(): void
    {
        $previous = m::mock(CommentPost::class);
        $post = m::mock(DiscussionReferencedPost::class)->makePartial();
        $post->shouldReceive('save')->once();

        $new = $post->saveAfter($previous);
        assertEquals($post, $new);
    }

    public function testSaveAfterDifferentUser(): void
    {
        $previous = m::mock(DiscussionReferencedPost::class);
        $previous->shouldReceive('getAttribute')->with('user_id')->andReturn(1);
        $post = m::mock(DiscussionReferencedPost::class)->makePartial();
        $post->shouldReceive('getAttribute')->with('user_id')->andReturn(2);
        $post->shouldReceive('save')->once();

        $new = $post->saveAfter($previous);
        assertEquals($post, $new);
    }

    public function testSaveAfterValid(): void
    {
        $previous = m::mock(DiscussionReferencedPost::class);
        $previous->shouldReceive('getAttribute')->with('user_id')->andReturn(1);
        $previous->shouldReceive('getAttribute')->with('content')->andReturn([1]);
        $previous->shouldReceive('setAttribute')->with('content', [1, 2])->once();
        $previous->shouldReceive('save')->once();
        $post = m::mock(DiscussionReferencedPost::class)->makePartial();
        $post->shouldReceive('getAttribute')->with('user_id')->andReturn(1);
        $post->shouldReceive('getAttribute')->with('content')->andReturn([2]);

        $new = $post->saveAfter($previous);
        assertEquals($previous, $new);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testReply(): void
    {
        m::mock('overload:Flarum\Post\AbstractEventPost');
        $post = DiscussionReferencedPost::reply(1, 2, 3);
        assertEquals(1, $post->discussion_id);
        assertEquals(2, $post->user_id);
        assertEquals([3], $post->content);
    }
}
