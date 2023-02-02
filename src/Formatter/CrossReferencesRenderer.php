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
use Flarum\Http\RequestUtil;
use Flarum\Locale\Translator;
use Psr\Http\Message\ServerRequestInterface;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

class CrossReferencesRenderer
{
    /**
     * @var Translator
     */
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Configure rendering for cross references.
     *
     * @param s9e\TextFormatter\Renderer $renderer
     * @param mixed $context
     * @param string|null $xml
     * @return string $xml to be rendered
     */
    public function __invoke(Renderer $renderer, $context, string $xml, ServerRequestInterface $request)
    {
        $actor = RequestUtil::getActor($request);
        $filterCrossReferences = function ($attributes) use ($actor) {
            /** @var Discussion|null */
            $discussion = Discussion::find($attributes['id']);
            if (!is_null($discussion) && $actor->can('viewForum', $discussion)) {
                $attributes['title'] = $discussion->title;
            } else {
                $attributes['title'] = $this->translator->trans('club-1-cross-references.forum.unknown_discussion');
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
