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

use Club1\CrossReferences\Formatter\CrossReferencesConfigurator;
use DOMDocument;
use DOMElement;
use Flarum\Foundation\Config;
use Flarum\Foundation\ErrorHandling\LogReporter;
use Flarum\Http\RouteCollection;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Testing\unit\TestCase;
use Flarum\User\User;
use Illuminate\Container\Container;
use Mockery as m;
use Mockery\MockInterface;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Parser;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThanOrEqual;

class CrossReferencesConfiguratorTest extends TestCase
{
    /** @var SettingsRepositoryInterface&MockInterface */
    protected $settingsRepo;
    /** @var UrlGenerator&MockInterface */
    protected $urlGenerator;
    /** @var LogReporter&MockInterface */
    protected $log;
    /** @var Config&MockInterface */
    protected $config;
    /** @var Configurator */
    protected $configurator;
    /** @var \Flarum\Discussion\Discussion&MockInterface */
    protected $discussionModel;

    public function setUp(): void
    {
        parent::setUp();
        $this->settingsRepo = m::mock(SettingsRepositoryInterface::class);
        $this->settingsRepo->shouldReceive('get')->with('club-1-cross-references.show_discussion_id')->andReturn(false)->byDefault();
        $this->settingsRepo->shouldReceive('get')->with('club-1-cross-references.enable_short_references')->andReturn(true)->byDefault();

        $routeCollection = m::mock(RouteCollection::class);
        $routeCollection->shouldReceive('route')->with('discussion', ['id' => ''])->andReturn('https://forum.club1.fr/d/');

        $this->urlGenerator = m::mock(UrlGenerator::class);
        $this->urlGenerator->shouldReceive('to')->with('forum')->andReturn($routeCollection);

        $this->log = m::mock(LogReporter::class);
        $this->config = m::mock(Config::class)->makePartial();

        $container = new Container();
        $container->instance(LogReporter::class, $this->log);
        $container->instance(Config::class, $this->config);
        Container::setInstance($container);

        // Mock Eloquent Discussion Model by creating an alias in the autoloader.
        // This only works if the aliased class is not yet loaded.
        // See: <https://docs.mockery.io/en/latest/reference/creating_test_doubles.html#aliasing>
        $this->discussionModel = m::mock('alias:Flarum\Discussion\Discussion');

        $this->configurator = new Configurator;
    }

    public static function mockActor(bool $allowed = true): User
    {
        $actor = m::mock(User::class);
        $actor->shouldReceive('cannot')->andReturn(!$allowed);
        return $actor;
    }

    public function mockDiscussion(?array $data = ['title' => 'dummy']): void
    {
        $discussion = null;
        if (!is_null($data)) {
            $discussion = new \Flarum\Discussion\Discussion();
            $discussion->title = $data['title'];
        }
        $this->discussionModel->shouldReceive('whereVisibleTo->firstWhere')->andReturn($discussion);
    }

    public function basicParserConfiguration(): Parser
    {
        $this->mockDiscussion();
        $xrefConfigurator = new CrossReferencesConfigurator($this->settingsRepo, $this->urlGenerator);
        $xrefConfigurator($this->configurator);
        extract($this->configurator->finalize());
        $parser->registeredVars['actor'] = self::mockActor(true);
        return $parser;
    }

    public function basicRendererConfiguration(): Renderer
    {
        $xrefConfigurator = new CrossReferencesConfigurator($this->settingsRepo, $this->urlGenerator);
        $xrefConfigurator($this->configurator);
        extract($this->configurator->finalize());
        return $renderer;
    }

    /**
     * @dataProvider shortReferencesProvider
     * @param string $text The text to parse.
     * @param string[] $expected The expected array of ids found in the text.
     */
    public function testShortReferences(string $text, array $expected): void
    {
        $parser = $this->basicParserConfiguration();
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
        $parser = $this->basicParserConfiguration();
        $xml = $parser->parse($text);
        $actual = Utils::getAttributeValues($xml, 'CROSSREFERENCEURL', 'id');
        assertEquals($expected, $actual);
    }

    public function urlReferencesProvider(): array
    {
        return [
            ['https://forum.club1.fr/d/9-d-3/foo.', []],
            ['https://forum.club1.fr/d/9-d-3/,foo', ['9']],
            ['https://forum.club1.fr/d/9-d-/foo.', []],
            ['https://forum.club1.fr/d/9-d-3/ ', ['9']],
            ['https://forum.club1.fr/d/9-d-3/}', ['9']],
            ['https://forum.club1.fr/d/9-d-3 ', ['9']],
            ['https://forum.club1.fr/d/9-d-3/.', ['9']],
            ['https://forum.club1.fr/d/9-d-3//', []],
            ['https://forum.club1.fr/d/9-d-3.', ['9']],
            ['https://forum.club1.fr/d/9-d-.', ['9']],
            ['https://forum.club1.fr/d/9-d', ['9']],
            ['https://forum.club1.fr/d/9-', ['9']],
            ['https://forum.club1.fr/d/9d', []],
            ['https://forum.club1.fr/d/9', ['9']],
            [' https://forum.club1.fr/d/9', ['9']],
            ['coucou https://forum.club1.fr/d/9', ['9']],
            ['coucou phttps://forum.club1.fr/d/9', []],
            ['coucou.https://forum.club1.fr/d/9', ['9']],
            ['coucou (https://forum.club1.fr/d/9)', ['9']],
        ];
    }

    /**
     * @dataProvider commentUrlReferencesProvider
     * @param string $text The text to parse.
     * @param string[] $expected The expected array of ids found in the text.
     */
    public function testCommentUrlReferences(string $text, array $expected): void
    {
        $parser = $this->basicParserConfiguration();
        $xml = $parser->parse($text);
        $actual = Utils::getAttributeValues($xml, 'CROSSREFERENCEURLCOMMENT', 'id');
        assertEquals($expected, $actual);
    }

    public function commentUrlReferencesProvider(): array
    {
        return [
            ['https://forum.club1.fr/d/9-d-3/2foo.', []],
            ['https://forum.club1.fr/d/9-d-3/2,foo', ['9']],
            ['https://forum.club1.fr/d/9-d-/2foo.', []],
            ['https://forum.club1.fr/d/9-d-3/22 ', ['9']],
            ['https://forum.club1.fr/d/9-d-3/2}', ['9']],
            ['https://forum.club1.fr/d/9-d-3 ', []],
            ['https://forum.club1.fr/d/9-d-3/2/.', []],
            ['https://forum.club1.fr/d/9-d-3/2//', []],
            ['https://forum.club1.fr/d/9-d-3/2.', ['9']],
            ['https://forum.club1.fr/d/9-d-/2.', ['9']],
            ['https://forum.club1.fr/d/9-d/2', ['9']],
            ['https://forum.club1.fr/d/9-/2', ['9']],
            ['https://forum.club1.fr/d/9d/2', []],
            ['https://forum.club1.fr/d/9/2', ['9']],
            [' https://forum.club1.fr/d/9/2', ['9']],
            ['coucou https://forum.club1.fr/d/9/2', ['9']],
            ['coucou phttps://forum.club1.fr/d/9/2', []],
            ['coucou.https://forum.club1.fr/d/9/2', ['9']],
            ['coucou (https://forum.club1.fr/d/9/2)', ['9']],
        ];
    }

    /**
     * @dataProvider actorPermissionsProvider
     * @param string $text The text to parse.
     * @param bool $allowed If the actor is allowed or not.
     * @param string[] $expected The expected array of ids found in the text.
     */
    public function testActorPermissions(string $text, bool $allowed, array $expected): void
    {
        $this->mockDiscussion();
        $xrefConfigurator = new CrossReferencesConfigurator($this->settingsRepo, $this->urlGenerator);
        $xrefConfigurator($this->configurator);
        extract($this->configurator->finalize());
        $parser->registeredVars['actor'] = self::mockActor($allowed);

        $xml = $parser->parse($text);
        $actual = Utils::getAttributeValues($xml, 'CROSSREFERENCESHORT', 'id');
        assertEquals($expected, $actual);
    }

    public function actorPermissionsProvider(): array
    {
        return [
            ['#42', true, ['42']],
            ['#42', false, []],
        ];
    }

    public function testActorIsNull(): void
    {
        $xrefConfigurator = new CrossReferencesConfigurator($this->settingsRepo, $this->urlGenerator);
        $xrefConfigurator($this->configurator);
        extract($this->configurator->finalize());
        $parser->registeredVars['actor'] = null;
        $this->log->shouldReceive('report')->once();

        $xml = $parser->parse('#42');
        $actual = Utils::getAttributeValues($xml, 'CROSSREFERENCESHORT', 'id');
        $expected = [];
        assertEquals($expected, $actual);
    }

    /**
     * @dataProvider discussionExistsProvider
     * @param string $text The text to parse.
     * @param bool $exists If the discussion exists or not.
     * @param string[] $expected The expected array of ids found in the text.
     */
    public function testDiscussionExists(string $text, bool $exists, array $expected): void
    {
        if ($exists) {
            $this->mockDiscussion();
        } else {
            $this->mockDiscussion(null);
        }
        $xrefConfigurator = new CrossReferencesConfigurator($this->settingsRepo, $this->urlGenerator);
        $xrefConfigurator($this->configurator);
        extract($this->configurator->finalize());
        $parser->registeredVars['actor'] = self::mockActor(true);

        $xml = $parser->parse($text);
        $actual = Utils::getAttributeValues($xml, 'CROSSREFERENCESHORT', 'id');
        assertEquals($expected, $actual);
    }

    public function discussionExistsProvider(): array
    {
        return [
            ['#42', true, ['42']],
            ['#42', false, []],
        ];
    }

    /**
     * @dataProvider renderingUnknownProvider
     * @param string $xml The xml to render.
     * @param bool $showId If the show ID parameter should be enabled.
     * @param string $title The expected title.
     */
    public function testRenderingUnknown(string $xml, bool $showId, string $title): void
    {
        $this->settingsRepo->shouldReceive('get')->with('club-1-cross-references.show_discussion_id')->andReturn($showId);
        $renderer = $this->basicRendererConfiguration();
        $html = $renderer->render($xml);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        assertCount(0, $dom->getElementsByTagName('a'));
        assertGreaterThanOrEqual(1, $dom->getElementsByTagName('span')->length);
        $span = $dom->getElementsByTagName('span')->item(0);
        assert($span instanceof DOMElement);
        $classes = explode(' ', $span->getAttribute('class'));
        assertContains('DiscussionLink', $classes);
        assertContains('DiscussionUnknown', $classes);
        assertEquals($title, $span->textContent);
    }

    public function renderingUnknownProvider(): array
    {
        return [
            ['<CROSSREFERENCESHORT id="42" title="[unknown]" unknown="1">#42</CROSSREFERENCESHORT>',
             false, '[unknown]'],
            ['<CROSSREFERENCEURL id="42" title="[unknown]" url="https://forum.club1.fr/d/42-coucou" unknown="1"></CROSSREFERENCEURL>',
             false, '[unknown]'],
            ['<CROSSREFERENCEURLCOMMENT id="42" title="[unknown]" url="https://forum.club1.fr/d/42-coucou/12" unknown="1" comment="comment"></CROSSREFERENCEURLCOMMENT>',
             false, '[unknown] (comment)'],
            ['<CROSSREFERENCESHORT id="42" title="[unknown]" unknown="1">#42</CROSSREFERENCESHORT>',
             true, '[unknown] #42'],
            ['<CROSSREFERENCEURL id="2" title="[unknown]" url="https://forum.club1.fr/d/42-coucou" unknown="1"></CROSSREFERENCEURL>',
             true, '[unknown] #2'],
            ['<CROSSREFERENCEURLCOMMENT id="3" title="[unknown]" url="https://forum.club1.fr/d/42-coucou/12" unknown="1" comment="comment"></CROSSREFERENCEURLCOMMENT>',
             true, '[unknown] #3 (comment)'],
        ];
    }

    /**
     * @dataProvider renderingValidProvider
     * @param string $xml The xml to render.
     * @param bool $showId If the show ID parameter should be enabled.
     * @param string $title The expected title.
     * @param string $url The expected url.
     */
    public function testRenderingValid(string $xml, bool $showId, string $title, string $url): void
    {
        $this->settingsRepo->shouldReceive('get')->with('club-1-cross-references.show_discussion_id')->andReturn($showId);
        $renderer = $this->basicRendererConfiguration();
        $html = $renderer->render($xml);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        assertCount(1, $dom->getElementsByTagName('a'));
        $a = $dom->getElementsByTagName('a')->item(0);
        assert($a instanceof DOMElement);
        $classes = explode(' ', $a->getAttribute('class'));
        assertContains('DiscussionLink', $classes);
        assertEquals($title, $a->textContent);
        assertEquals($url, $a->getAttribute('href'));
    }

    public function renderingValidProvider(): array
    {
        return [
            ['<CROSSREFERENCESHORT id="42" title="Coucou 42">#42</CROSSREFERENCESHORT>',
             false, 'Coucou 42', 'https://forum.club1.fr/d/42'],
            ['<CROSSREFERENCEURL id="42" title="Dummy" url="https://forum.club1.fr/d/42-coucou"></CROSSREFERENCEURL>',
             false, 'Dummy', 'https://forum.club1.fr/d/42-coucou'],
            ['<CROSSREFERENCEURLCOMMENT id="42" title="Coucou" url="https://forum.club1.fr/d/42-coucou/12" comment="comment"></CROSSREFERENCEURLCOMMENT>',
             false, 'Coucou (comment)', 'https://forum.club1.fr/d/42-coucou/12'],
            ['<CROSSREFERENCESHORT id="42" title="Coucou 42">#42</CROSSREFERENCESHORT>',
             true, 'Coucou 42 #42', 'https://forum.club1.fr/d/42'],
            ['<CROSSREFERENCEURL id="77" title="Dummy" url="https://forum.club1.fr/d/7-coucou"></CROSSREFERENCEURL>',
             true, 'Dummy #77', 'https://forum.club1.fr/d/7-coucou'],
            ['<CROSSREFERENCEURLCOMMENT id="1" title="Coucou" url="https://forum.club1.fr/d/1/12" comment="comment"></CROSSREFERENCEURLCOMMENT>',
             true, 'Coucou #1 (comment)', 'https://forum.club1.fr/d/1/12'],
        ];
    }
}
