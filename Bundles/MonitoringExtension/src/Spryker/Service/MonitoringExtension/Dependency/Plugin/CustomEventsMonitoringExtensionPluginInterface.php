<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\MonitoringExtension\Dependency\Plugin;

interface CustomEventsMonitoringExtensionPluginInterface
{
    /**
     * Specification:
     * - Adds a custom event
     *
     * @api
     *
     * @param string $name
     * @param array<mixed> $attributes
     *
     * @return void
     */
    public function addEvent(string $name, array $attributes): void;
}
