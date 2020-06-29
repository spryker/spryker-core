<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Business;

use Symfony\Component\Console\Event\ConsoleTerminateEvent;

/**
 * @method \Spryker\Zed\Monitoring\Business\MonitoringBusinessFactory getFactory()
 */
interface MonitoringFacadeInterface
{
    /**
     * Specification:
     * - Handles console terminate event.
     * - Marks monitoring transaction as a console command.
     * - Sets transaction name based on the command name.
     * - Adds custom parameters to the transaction like host, command arguments and options.
     *
     * @api
     *
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function handleConsoleTerminateEvent(ConsoleTerminateEvent $event): void;
}
