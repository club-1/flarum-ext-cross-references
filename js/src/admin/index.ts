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

import app from 'flarum/admin/app';

app.initializers.add('club-1-cross-references', function(app) {
  app.extensionData
    .for('club-1-cross-references')
    .registerSetting({
      setting: 'club-1-cross-references.show_discussion_id',
      label: app.translator.trans('club-1-cross-references.admin.show_discussion_id.label'),
      help: app.translator.trans('club-1-cross-references.admin.show_discussion_id.help'),
      type: 'boolean',
    })
    .registerSetting({
      setting: 'club-1-cross-references.enable_short_references',
      label: app.translator.trans('club-1-cross-references.admin.enable_short_references.label'),
      help: app.translator.trans('club-1-cross-references.admin.enable_short_references.help'),
      type: 'boolean',
    });

});
