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

use Club1\CrossReferences\Listener\PostEventListener;
use Flarum\Discussion\Discussion;
use Flarum\Http\UrlGenerator;
use Flarum\Post\CommentPost;
use Flarum\Post\Event\Posted;
use Flarum\Post\Event\Revised;
use Flarum\Post\MergeableInterface;
use Flarum\Testing\unit\TestCase;
use Flarum\User\User;
use Mockery as m;

$short   = 'CROSSREFERENCESHORT';
$url     = 'CROSSREFERENCEURL';
$comment = 'CROSSREFERENCEURLCOMMENT';

/**
 * @runClassInSeparateProcess
 * @preserveGlobalState disabled
 */
class PostEventListenerTest extends TestCase
{

    /** @var UrlGenerator */
    protected $urlGenerator;
    /** @var \Flarum\Discussion\Discussion */
    protected $discussionModel;
    /** @var PostEventListener */
    protected $listener;

    public function setUp(): void
    {
        $routeCollection = m::mock(RouteCollection::class);
        $routeCollection->shouldReceive('route')->with('discussion', ['id' => ''])->andReturn('https://forum.club1.fr/d/');

        $this->urlGenerator = m::mock(UrlGenerator::class);
        $this->urlGenerator->shouldReceive('to')->with('forum')->andReturn($routeCollection);
        $this->listener = m::mock(PostEventListener::class, [$this->urlGenerator])->makePartial()->shouldAllowMockingProtectedMethods();

        $xrefPost = m::mock('alias:Club1\CrossReferences\Post\DiscussionReferencedPost');
        $xrefPost->shouldReceive('reply')->andReturn(m::mock(MergeableInterface::class));
    }

    public function registerTargetDiscussion($id, $sourceAlreadyExists = false)
    {
        $sources = m::mock('sources');
        $sources->shouldReceive('where->exists')->andReturn($sourceAlreadyExists);
        if (!$sourceAlreadyExists) {
            $sources->shouldReceive('save')->once();
        }
        $d = m::mock(Discussion::class);
        $d->shouldReceive('getAttribute')->with('id')->andReturn($id);
        $d->shouldReceive('sources')->andReturn($sources);
        $this->listener->shouldReceive('findDiscussion')->with($id)->once()->andReturn($d);
        return $d;
    }

    public static function mockPost($xml, $discussionId, $discussion = null): CommentPost
    {
        $post = m::mock(CommentPost::class);
        $post->shouldReceive('getAttribute')->with('parsed_content')->once()->andReturn($xml);
        $post->shouldReceive('getAttribute')->with('discussion_id')->andReturn($discussionId);
        $post->shouldReceive('getAttribute')->with('discussion')->andReturn($discussion);
     return $post;
    }

    public static function mockActor($id): User
    {
        $actor = m::mock(User::class);
        $actor->shouldReceive('getAttribute')->with('id')->andReturn($id);
        return $actor;
    }

    public function testDiscussionNotExists(): void
    {
        $id = 1;
        $xml = "<CROSSREFERENCEURL id=\"$id\"></CROSSREFERENCEURL>";
        $post = self::mockPost($xml, 2);
        $actor = self::mockActor(3);
        $this->listener->shouldReceive('findDiscussion')->with($id)->once()->andReturn(null);

        $event = new Posted($post, $actor);
        $this->listener->handle($event);
    }

    public function testTargetDiscussionSourceExists(): void
    {
        $id = 1;
        $xml = "<CROSSREFERENCEURL id=\"$id\"></CROSSREFERENCEURL>";
        $post = self::mockPost($xml, 2);
        $actor = self::mockActor(3);
        $this->registerTargetDiscussion($id, true);

        $event = new Posted($post, $actor);
        $this->listener->handle($event);
    }

    public function testRevisedEvent(): void
    {
        $id = 1;
        $xml = "<CROSSREFERENCEURL id=\"$id\"></CROSSREFERENCEURL>";
        $post = self::mockPost($xml, 2);
        $actor = self::mockActor(3);
        $d = $this->registerTargetDiscussion($id);
        $d->shouldReceive('mergePost')->once();

        $event = new Revised($post, $actor);
        $this->listener->handle($event);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testValid(string $xml, array $ids, int $discussionId = 6, int $actorId = 7): void
    {
        $post = self::mockPost($xml, $discussionId);
        $actor = self::mockActor($actorId);
        foreach($ids as $id) {
            $d = $this->registerTargetDiscussion($id);
            $d->shouldReceive('mergePost')->once();
        }

        $event = new Posted($post, $actor);
        $this->listener->handle($event);
    }

    public function dataProvider(): array
    {
        global $short, $url, $comment;
        return [
            // basic case
            [
                "<$short id=\"12\"></$short>",
                [12],
            ],
            // all tags should work
            [
                "<$short id=\"13\"></$short> <$url id=\"14\"></$url> <$comment id=\"15\"></$comment>",
                [13, 14, 15],
            ],
            // sould deduplicate
            [
                "<$short id=\"16\"></$short> <$short id=\"16\"></$short>",
                [16],
            ],
            // do not self reference
            [
                "<$short id=\"17\"></$short> <$short id=\"18\"></$short>",
                [17],
                18,
            ],
        ];
    }

}
