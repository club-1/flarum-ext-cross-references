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

use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use s9e\TextFormatter\Configurator;

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
        $this->configureCrossReferenceShort($config);
        $this->configureCrossReferenceURL($config);
        $this->configureCrossReferenceURLComment($config);
        error_log('configured cross references');
    }

    protected function configureCrossReferenceShort(Configurator $config)
    {
        $tagName = 'CROSSREFERENCESHORT';

        $tag = $config->tags->add($tagName);
        $tag->attributes->add('id')->filterChain->append('#uint');
        $tag->template = '
            <a href="{$DISCUSSION_URL}{@id}" class="DiscussionLink">
                #<xsl:value-of select="@id"/>
                <xsl:if test="$SHOW_DISCUSSION_ID = 1"> <span class="DiscussionId">#<xsl:value-of select="@id"/></span></xsl:if>
            </a>';

        $config->Preg->match('/\B#(?<id>[0-9]+)\b/i', $tagName);
    }

    protected function configureCrossReferenceURL(Configurator $config)
    {
        $tagName = 'CROSSREFERENCEURL';

        $tag = $config->tags->add($tagName);
        $tag->attributes->add('id')->filterChain->append('#uint');
        $tag->attributes->add('url')->filterChain->append('#url');
        $tag->template = '
            <a href="{@url}" class="DiscussionLink">
                #<xsl:value-of select="@id"/>
                <xsl:if test="$SHOW_DISCUSSION_ID = 1"> <span class="DiscussionId">#<xsl:value-of select="@id"/></span></xsl:if>
            </a>';

        $config->Preg->match("/(?:^|\b)(?<url>$this->discussionPathEsc(?<id>[0-9]+)[^\s\/]*\/?)(?=\s|$)/i", $tagName);
    }

    protected function configureCrossReferenceURLComment(Configurator $config)
    {
        $tagName = 'CROSSREFERENCEURLCOMMENT';

        $tag = $config->tags->add($tagName);
        $tag->attributes->add('id')->filterChain->append('#uint');
        $tag->attributes->add('url')->filterChain->append('#url');
        $tag->template = '
            <a href="{@url}" class="DiscussionLink">
                #<xsl:value-of select="@id"/> <xsl:if test="$SHOW_DISCUSSION_ID = 1">
                    <span class="DiscussionId">#<xsl:value-of select="@id"/></span>
                </xsl:if> <span class="DiscussionComment">(comment)</span>
            </a>';

        $config->Preg->match("/(?:^|\b)(?<url>$this->discussionPathEsc(?<id>[0-9]+)[^\s\/]*\/[0-9]+)(?=\s|$)/i", $tagName);
    }
}
