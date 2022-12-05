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

use Club1\CrossReferences\Post\DiscussionReferencedPost;
use Flarum\Api\Controller\ShowDiscussionController;
use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Discussion\Discussion;
use Flarum\Extend;
use Flarum\Post\Event\Saving;

return [
    (new Extend\Event())
        ->listen(Saving::class, Listener\PostSavedListener::class),

    (new Extend\Post())
        ->type(DiscussionReferencedPost::class),

    (new Extend\Model(Discussion::class))
        ->belongsToMany('references', Discussion::class, 'discussion_reference', 'target_id', 'source_id'),

    (new Extend\ApiSerializer(DiscussionSerializer::class))
        ->hasMany('references', DiscussionSerializer::class),

    (new Extend\ApiController(ShowDiscussionController::class))
        ->addInclude(['references'])
        ->load('references'),

    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js'),

    new Extend\Locales(__DIR__.'/locale'),
];
