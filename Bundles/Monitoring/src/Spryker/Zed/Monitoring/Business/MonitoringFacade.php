<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

/**
 * @method \Spryker\Zed\Monitoring\Business\MonitoringBusinessFactory getFactory()
 */
class MonitoringFacade extends AbstractFacade implements MonitoringFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function handleOnConsoleTerminateEvent(ConsoleTerminateEvent $event): void
    {
        $this->getFactory()->createEvent()->handleConsoleTerminate($event);
    }
}
