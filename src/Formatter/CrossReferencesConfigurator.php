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

namespace Club1\CrossReferences\Formatter;

use Flarum\Discussion\Discussion;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Parser\Tag;

class CrossReferencesConfigurator
{
    const PARAM_DISCUSSION_URL = 'DISCUSSION_URL';
    const PARAM_SHOW_DISCUSSION_ID = 'SHOW_DISCUSSION_ID';

    /** @var SettingsRepositoryInterface */
    protected $settings;

    /** @var UrlGenerator */
    protected $urlGen;

    /** @var string */
    protected $discussionPath;

    /** @var string */
    protected $discussionPathEsc;

    public function __construct(SettingsRepositoryInterface $settings, UrlGenerator $urlGenerator)
    {
        $this->settings = $settings;
        $this->urlGen = $urlGenerator;
        $this->discussionPath = $this->urlGen->to('forum')->route('discussion', ['id' => '']);
        $this->discussionPathEsc = addcslashes($this->discussionPath, '/');
    }

    public function __invoke(Configurator $config)
    {
        $config->rendering->parameters[self::PARAM_DISCUSSION_URL] = $this->discussionPath;
        $config->rendering->parameters[self::PARAM_SHOW_DISCUSSION_ID] = $this->settings->get('club-1-cross-references.show_discussion_id');
        if ($this->settings->get('club-1-cross-references.enable_short_references')) {
            $this->configureCrossReferenceShort($config);
        }
        $this->configureCrossReferenceURL($config);
        $this->configureCrossReferenceURLComment($config);
    }

    public static function filterCrossReferences(Tag $tag, User $actor)
    {
        /** @var Discussion|null */
        $d = Discussion::find($tag->getAttribute('id'));
        if (is_null($d) || $actor->cannot('viewForum', $d)) {
            $tag->invalidate();
            return false;
        }
        // Set placeholder values for TextFormatter to be happy.
        // The real values is set during render.
        $tag->setAttribute('title', $d->title);
        $tag->setAttribute('comment', '');
        return true;
    }

    protected function configureCrossReferenceShort(Configurator $config)
    {
        $tagName = 'CROSSREFERENCESHORT';

        $tag = $config->tags->add($tagName);
        $tag->attributes->add('id')->filterChain->append('#uint');
        $tag->attributes->add('title');
        $tag->template = '
            <a href="{$DISCUSSION_URL}{@id}" class="DiscussionLink">
                <xsl:value-of select="@title"/> <xsl:if test="$SHOW_DISCUSSION_ID = 1">
                    <span class="DiscussionId">#<xsl:value-of select="@id"/></span>
                </xsl:if>
            </a>';

        $tag->filterChain
            ->prepend([static::class, 'filterCrossReferences'])
            ->setJS('flarum.extensions["club-1-cross-references"].filterCrossReferences')
            ->addParameterByName('actor');
        $config->Preg->match('/\B#(?<id>\d+)\b/i', $tagName);
    }

    protected function configureCrossReferenceURL(Configurator $config)
    {
        $tagName = 'CROSSREFERENCEURL';

        $tag = $config->tags->add($tagName);
        $tag->attributes->add('id')->filterChain->append('#uint');
        $tag->attributes->add('url')->filterChain->append('#url');
        $tag->attributes->add('title');
        $tag->template = '
            <a href="{@url}" class="DiscussionLink">
                <xsl:value-of select="@title"/> <xsl:if test="$SHOW_DISCUSSION_ID = 1">
                    <span class="DiscussionId">#<xsl:value-of select="@id"/></span>
                </xsl:if>
            </a>';

        $tag->filterChain
            ->prepend([static::class, 'filterCrossReferences'])
            ->setJS('flarum.extensions["club-1-cross-references"].filterCrossReferences')
            ->addParameterByName('actor');
        $config->Preg->match("/\b(?<url>$this->discussionPathEsc(?<id>\d+)(-[-\p{L}\p{N}\p{M}]*)?\/?)(?=[^-\p{L}\p{N}\/]|$)/i", $tagName);
    }

    protected function configureCrossReferenceURLComment(Configurator $config)
    {
        $tagName = 'CROSSREFERENCEURLCOMMENT';

        $tag = $config->tags->add($tagName);
        $tag->attributes->add('id')->filterChain->append('#uint');
        $tag->attributes->add('url')->filterChain->append('#url');
        $tag->attributes->add('title');
        $tag->attributes->add('comment');
        $tag->template = '
            <a href="{@url}" class="DiscussionLink">
                <xsl:value-of select="@title"/> <xsl:if test="$SHOW_DISCUSSION_ID = 1">
                    <span class="DiscussionId">#<xsl:value-of select="@id"/></span>
                </xsl:if> <span class="DiscussionComment">(<xsl:value-of select="@comment"/>)</span>
            </a>';

        $tag->filterChain
            ->prepend([static::class, 'filterCrossReferences'])
            ->setJS('flarum.extensions["club-1-cross-references"].filterCrossReferences')
            ->addParameterByName('actor');
        $config->Preg->match("/\b(?<url>$this->discussionPathEsc(?<id>\d+)(-[-\p{L}\p{N}\p{M}]*)?\/\d+)(?=[^-\p{L}\p{N}\/]|$)/i", $tagName);
    }
}
