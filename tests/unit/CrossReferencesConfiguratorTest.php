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

namespace Club1\Test\unit;

use Club1\CrossReferences\Formatter\CrossReferencesConfigurator;
use Flarum\Http\RouteCollection;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Testing\unit\TestCase;
use Flarum\User\User;
use Mockery as m;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Parser;
use s9e\TextFormatter\Utils;

use function PHPUnit\Framework\assertEquals;

class CrossReferencesConfiguratorTest extends TestCase
{
    /** @var SettingsRepositoryInterface */
    protected $settingsRepo;
    /** @var UrlGenerator */
    protected $urlGenerator;
    /** @var Configurator */
    protected $configurator;

    public function setUp(): void
    {
        $this->settingsRepo = m::mock(SettingsRepositoryInterface::class);
        $this->settingsRepo->shouldReceive('get')->with('club-1-cross-references.show_discussion_id')->andReturn(false);
        $this->settingsRepo->shouldReceive('get')->with('club-1-cross-references.enable_short_references')->andReturn(true);

        $routeCollection = m::mock(RouteCollection::class);
        $routeCollection->shouldReceive('route')->with('discussion', ['id' => ''])->andReturn('https://forum.club1.fr/d/');

        $this->urlGenerator = m::mock(UrlGenerator::class);
        $this->urlGenerator->shouldReceive('to')->with('forum')->andReturn($routeCollection);

        // Mock Eloquent Discussion Model by creating an alias in the autoloader.
        // This only works if the aliased class is not yet loaded.
        // See: <https://docs.mockery.io/en/latest/reference/creating_test_doubles.html#aliasing>
        $discussionModel = m::mock('alias:Flarum\Discussion\Discussion');
        $discussion = new \Flarum\Discussion\Discussion();
        $discussion->title = 'dummy discussion';
        $discussionModel->shouldReceive('find')->andReturn($discussion);

        $this->configurator = new Configurator;
    }

    public static function mockActor(bool $allowed = true): User
    {
        $actor = m::mock(User::class);
        $actor->shouldReceive('cannot')->andReturn(!$allowed);
        return $actor;
    }

    public function basicParser(): Parser
    {
        $xrefConfigurator = new CrossReferencesConfigurator($this->settingsRepo, $this->urlGenerator);
        $xrefConfigurator($this->configurator);
        extract($this->configurator->finalize());
        $parser->registeredVars['actor'] = self::mockActor(true);
        return $parser;
    }

    /**
     * @dataProvider shortReferencesProvider
     * @param string $text The text to parse.
     * @param string[] $expected The expected array of ids found in the text.
     */
    public function testShortReferences(string $text, array $expected): void
    {
        $parser = $this->basicParser();
        $xml = $parser->parse($text);
        $actual = Utils::getAttributeValues($xml, 'CROSSREFERENCESHORT', 'id');
        assertEquals($expected, $actual);
    }

    public function shortReferencesProvider(): array
    {
        return [
            ['#1', ['1']],
            ['m#2', []],
            ['{#3', ['3']],
            ['##4', ['4']],
            ['9#5', []],
            [':#6', ['6']],
            ['?(#3234', ['3234']],
            [' #7', ['7']],
            ['#8.', ['8']],
        ];
    }

    /**
     * @dataProvider urlReferencesProvider
     * @param string $text The text to parse.
     * @param string[] $expected The expected array of ids found in the text.
     */
    public function testUrlReferences(string $text, array $expected): void
    {
        $parser = $this->basicParser();
        $xml = $parser->parse($text);
        $actual = Utils::getAttributeValues($xml, 'CROSSREFERENCEURL', 'id');
        assertEquals($expected, $actual);
    }

    public function urlReferencesProvider(): array
    {
        return [
            ['https://forum.club1.fr/d/3-discussion-3/foo.', []],
            ['https://forum.club1.fr/d/3-discussion-3/,foo', ['3']],
            ['https://forum.club1.fr/d/3-discussion-/foo.', []],
            ['https://forum.club1.fr/d/3-discussion-3/ ', ['3']],
            ['https://forum.club1.fr/d/3-discussion-3/}', ['3']],
            ['https://forum.club1.fr/d/3-discussion-3 ', ['3']],
            ['https://forum.club1.fr/d/3-discussion-3/.', ['3']],
            ['https://forum.club1.fr/d/3-discussion-3//', []],
            ['https://forum.club1.fr/d/3-discussion-3.', ['3']],
            ['https://forum.club1.fr/d/3-discussion-.', ['3']],
            ['https://forum.club1.fr/d/3-discussion', ['3']],
            ['https://forum.club1.fr/d/3-', ['3']],
            ['https://forum.club1.fr/d/3discussion', []],
            ['https://forum.club1.fr/d/3', ['3']],
            [' https://forum.club1.fr/d/3', ['3']],
            ['coucou https://forum.club1.fr/d/3', ['3']],
            ['coucou phttps://forum.club1.fr/d/3', []],
            ['coucou.https://forum.club1.fr/d/3', ['3']],
            ['coucou (https://forum.club1.fr/d/3)', ['3']],
        ];
    }
}
