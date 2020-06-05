<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Business\Event;

use Symfony\Component\Console\Event\ConsoleTerminateEvent;

interface EventInterface
{
    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function handleConsoleTerminate(ConsoleTerminateEvent $event): void;
}
