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

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        // Manually delete the invalid rows that should have been deleted by the
        // foreign key contraints and that would prevent us from adding them.
        $connection = $schema->getConnection();
        $connection->table('discussion_reference')->whereNotIn('target_id', function (QueryBuilder $query) {
            $query->select('id')->from('discussions');
        })->delete();
        $connection->table('discussion_reference')->whereNotIn('source_id', function (QueryBuilder $query) {
            $query->select('id')->from('discussions');
        })->delete();

        // Add the foreign key constraints.
        $schema->table('discussion_reference', function (Blueprint $table) {
            $table->foreign('target_id')->references('id')->on('discussions')->cascadeOnDelete();
            $table->foreign('source_id')->references('id')->on('discussions')->cascadeOnDelete();
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('discussion_reference', function (Blueprint $table) {
            $table->dropForeign(['target_id']);
            $table->dropForeign(['source_id']);
        });
    }
];
