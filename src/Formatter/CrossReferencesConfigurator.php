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
use s9e\TextFormatter\Configurator;

class CrossReferencesConfigurator
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

    public function __invoke(Configurator $config)
    {
        $this->configureCrossReference($config);
        error_log('configured cross references');
    }

    protected function configureCrossReference(Configurator $config)
    {
        $config->rendering->parameters['DISCUSSION_URL'] = $this->discussionPath;
        $discussionPathRegex = addcslashes($this->discussionPath, '/');

        $tagName = 'CROSSREFERENCE';

        $tag = $config->tags->add($tagName);

        $tag->attributes->add('id')->filterChain->append('#uint');
        // $tag->attributes->add('comment')->filterChain->append('#bool');
        // $tag->attributes->add('title');

        $tag->template = '<a href="{$DISCUSSION_URL}{@id}" class="DiscussionLink">#<xsl:value-of select="@id"/></a>';

        $config->Preg->match('/\B#(?<id>[0-9]+)\b/i', $tagName);
        $config->Preg->match("/(?:^|\b)$discussionPathRegex(?<id>[0-9]+)[^\s\/]*(?<comment>\/[0-9]+)?/i", $tagName);
    }
}
