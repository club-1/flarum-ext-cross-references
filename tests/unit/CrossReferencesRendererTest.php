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

use Club1\CrossReferences\Formatter\CrossReferencesRenderer;
use Flarum\Foundation\ErrorHandling\LogReporter;
use Flarum\Locale\Translator;
use Flarum\Testing\unit\TestCase;
use Flarum\User\Access\Gate;
use Flarum\User\Guest;
use Flarum\User\User;
use Mockery as m;
use Mockery\MockInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

use function PHPUnit\Framework\assertEquals;

class CrossReferencesRendererTest extends TestCase
{
    /** @var User&MockInterface */
    protected $actor;
    /** @var Request&MockInterface */
    protected $request;
    /** @var Translator&MockInterface */
    protected $translator;
    /** @var Renderer&MockInterface */
    protected $renderer;
    /** @var LogReporter&MockInterface */
    protected $log;
    /** @var \Flarum\Discussion\Discussion&MockInterface */
    protected $discussionModel;

    public function setUp(): void
    {
        parent::setUp();
        $this->actor = m::mock(User::class);
        $this->request = m::mock(Request::class)->makePartial();
        $this->request->shouldReceive('getAttribute->getActor')->andReturn($this->actor);
        $this->translator = m::mock(Translator::class);
        $this->renderer = m::mock(Renderer::class);
        $this->log = m::mock(LogReporter::class);

        // Mock Eloquent Discussion Model by creating an alias in the autoloader.
        // This only works if the aliased class is not yet loaded.
        // See: <https://docs.mockery.io/en/latest/reference/creating_test_doubles.html#aliasing>
        $this->discussionModel = m::mock('alias:Flarum\Discussion\Discussion');
    }

    public static function setUpGuestPermissions(bool $allowed): void
    {
        $gate = m::mock(Gate::class);
        $gate->shouldReceive('allows')->andReturn($allowed);
        Guest::setGate($gate);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDiscussionNotFound(string $tag, string $title, int $id): void
    {
        $this->discussionModel->shouldReceive('find')->with($id)->once()->andReturn(null);
        $this->translator->shouldReceive('trans')->with('club-1-cross-references.forum.unknown_discussion')->once()->andReturn('unknown');
        $this->translator->shouldReceive('trans')->with('club-1-cross-references.forum.comment')->once()->andReturn('comment');
        $xml = "<$tag id=\"$id\"></$tag>";

        $renderer = new CrossReferencesRenderer($this->translator, $this->log);
        $rendered = $renderer($this->renderer, null, $xml, $this->request);
        assertEquals(['unknown'], Utils::getAttributeValues($rendered, $tag, 'title'));
        assertEquals([true], Utils::getAttributeValues($rendered, $tag, 'unknown'));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUserNotPermitted(string $tag, string $title, int $id): void
    {
        $discussion = new \Flarum\Discussion\Discussion();
        $discussion->title = $title;
        $discussion->id = $id;
        $this->discussionModel->shouldReceive('find')->with($id)->once()->andReturn($discussion);
        $this->translator->shouldReceive('trans')->with('club-1-cross-references.forum.unknown_discussion')->once()->andReturn('unknown');
        $this->translator->shouldReceive('trans')->with('club-1-cross-references.forum.comment')->once()->andReturn('comment');
        $this->actor->shouldReceive('can')->with('viewForum', $discussion)->once()->andReturn(false);
        $xml = "<$tag id=\"$id\"></$tag>";

        $renderer = new CrossReferencesRenderer($this->translator, $this->log);
        $rendered = $renderer($this->renderer, null, $xml, $this->request);
        assertEquals(['unknown'], Utils::getAttributeValues($rendered, $tag, 'title'));
        assertEquals([true], Utils::getAttributeValues($rendered, $tag, 'unknown'));
    }

    /**
     * @dataProvider dataProvider
     * @dataProvider guestNotAllowedProvider
     */
    public function testRequestIsNull(string $tag, string $title, int $id, bool $guestAllowed = true): void
    {
        self::setUpGuestPermissions($guestAllowed);
        $discussion = new \Flarum\Discussion\Discussion();
        $discussion->title = $title;
        $discussion->id = $id;
        $this->discussionModel->shouldReceive('find')->with($id)->once()->andReturn($discussion);
        $this->translator->shouldReceive('trans')->with('club-1-cross-references.forum.unknown_discussion')->times(intval(!$guestAllowed))->andReturn('unknown');
        $this->translator->shouldReceive('trans')->with('club-1-cross-references.forum.comment')->once()->andReturn('comment');
        $this->log->shouldReceive('report')->once();
        $xml = "<$tag id=\"$id\"></$tag>";

        $renderer = new CrossReferencesRenderer($this->translator, $this->log);
        $rendered = $renderer($this->renderer, null, $xml, null);
        if ($guestAllowed) {
            assertEquals([$title], Utils::getAttributeValues($rendered, $tag, 'title'));
            assertEquals([], Utils::getAttributeValues($rendered, $tag, 'unknown'));
        } else {
            assertEquals(['unknown'], Utils::getAttributeValues($rendered, $tag, 'title'));
            assertEquals([true], Utils::getAttributeValues($rendered, $tag, 'unknown'));
        }
    }

    public function guestNotAllowedProvider(): array
    {
        return [['CROSSREFERENCESHORT', 'This is private', 7, false]];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testValid(string $tag, string $title, int $id): void
    {
        $discussion = new \Flarum\Discussion\Discussion();
        $discussion->title = $title;
        $discussion->id = $id;
        $this->discussionModel->shouldReceive('find')->with($id)->once()->andReturn($discussion);
        $this->translator->shouldReceive('trans')->with('club-1-cross-references.forum.comment')->once()->andReturn('comment');
        $this->actor->shouldReceive('can')->with('viewForum', $discussion)->once()->andReturn(true);
        $xml = "<$tag id=\"$id\"></$tag>";

        $renderer = new CrossReferencesRenderer($this->translator, $this->log);
        $rendered = $renderer($this->renderer, null, $xml, $this->request);
        assertEquals([$title], Utils::getAttributeValues($rendered, $tag, 'title'));
        assertEquals([], Utils::getAttributeValues($rendered, $tag, 'unknown'));
    }

    public function dataProvider(): array
    {
        return [
            [ 'CROSSREFERENCESHORT', 'Super titre', 42],
            [ 'CROSSREFERENCEURL', 'Super titre !', 41],
            [ 'CROSSREFERENCEURLCOMMENT', 'Titre.', 40],
        ];
    }
}
