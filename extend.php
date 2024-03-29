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

namespace Club1\CrossReferences;

use Club1\CrossReferences\Formatter\CrossReferencesConfigurator;
use Club1\CrossReferences\Formatter\CrossReferencesRenderer;
use Club1\CrossReferences\Post\DiscussionReferencedPost;
use Flarum\Api\Controller\ShowDiscussionController;
use Flarum\Discussion\Discussion;
use Flarum\Extend;
use Flarum\Post;
use Flarum\Settings\Event\Saved as SettingsSaved;

return [
    (new Extend\Formatter)
        ->configure(CrossReferencesConfigurator::class)
        ->render(CrossReferencesRenderer::class),

    (new Extend\Event())
        ->listen(Post\Event\Posted::class, Listener\PostEventListener::class)
        ->listen(Post\Event\Revised::class, Listener\PostEventListener::class)
        ->listen(SettingsSaved::class, Listener\SettingsSavedListener::class),

    (new Extend\Post())
        ->type(DiscussionReferencedPost::class),

    (new Extend\Model(Discussion::class))
        ->belongsToMany('sources', Discussion::class, 'discussion_reference', 'target_id', 'source_id')
        ->belongsToMany('targets', Discussion::class, 'discussion_reference', 'source_id', 'target_id'),

    (new Extend\ApiController(ShowDiscussionController::class))
        ->addInclude(['sources', 'targets']),

    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/css/forum.css'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Extend\Settings())
        ->default('club-1-cross-references.enable_short_references', true)
        ->serializeToForum('showDiscussionId', 'club-1-cross-references.show_discussion_id', function($value) {
            return $value === '1';
        }),

    new Extend\Locales(__DIR__.'/locale'),
];
