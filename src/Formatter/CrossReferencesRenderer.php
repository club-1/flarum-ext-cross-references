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
use Flarum\Foundation\Config;
use Flarum\Foundation\ErrorHandling\LogReporter;
use Flarum\Http\RequestUtil;
use Flarum\Locale\Translator;
use Flarum\User\Guest;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

class CrossReferencesRenderer
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var LogReporter
     */
    protected $log;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(Translator $translator, LogReporter $log, Config $config)
    {
        $this->translator = $translator;
        $this->log = $log;
        $this->config = $config;
    }

    /**
     * Configure rendering for cross references.
     *
     * @param Renderer $renderer
     * @param mixed $context
     * @param string|null $xml
     * @return string $xml to be rendered
     */
    public function __invoke(Renderer $renderer, $context, ?string $xml, ?ServerRequestInterface $request)
    {
        if (is_null($request)) {
            if ($this->config->inDebugMode()) {
                $msg = 'request is "null", falling back to display discussion as for Guest. This is probably due to another extension not passing this parameter to "Formatter->render()". See stack trace below.';
                $this->log->report(new RuntimeException($msg));
            }
            $actor = new Guest();
        } else {
            $actor = RequestUtil::getActor($request);
        }
        $filterCrossReferences = function ($attributes) use ($actor) {
            /** @var Discussion|null */
            $discussion = Discussion::whereVisibleTo($actor)->firstWhere('id', $attributes['id']);
            if ($discussion) {
                $attributes['title'] = $discussion->title;
                $attributes['tags'] = '<span class="TagsLabel "><span style="--tag-bg: #12dc12;" class="TagLabel  colored text-contrast--light TagLabel--child"><span class="TagLabel-text"><i class="icon fa fa-check"></i> résolu</span></span></span>';
            } else {
                $attributes['unknown'] = true;
            }
            $attributes['comment'] = $this->translator->trans('club-1-cross-references.forum.comment');
            return $attributes;
        };
        $xml = Utils::replaceAttributes($xml, 'CROSSREFERENCESHORT', $filterCrossReferences);
        $xml = Utils::replaceAttributes($xml, 'CROSSREFERENCEURL', $filterCrossReferences);
        $xml = Utils::replaceAttributes($xml, 'CROSSREFERENCEURLCOMMENT', $filterCrossReferences);
        return $xml;
    }
}
