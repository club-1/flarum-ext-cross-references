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

namespace Club1\Test\unit;

use Club1\CrossReferences\Listener\SettingsSavedListener;
use Flarum\Formatter\Formatter;
use Flarum\Settings\Event\Saved;
use Flarum\Testing\unit\TestCase;
use Mockery as m;

class SettingsSavedListenerTest extends TestCase
{
    public function testSettingsSaved(): void
    {
        $formatter = m::mock(Formatter::class);
        $formatter->shouldReceive('flush')->once();
        $event = m::mock(Saved::class);

        $listener = new SettingsSavedListener($formatter);
        $listener->handle($event);
    }
}
