<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventBehavior\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface getFacade()
 */
class EventBehaviorTriggerTimeoutConsole extends Console
{

    const COMMAND_NAME = 'event:trigger:timeout';
    const DESCRIPTION = 'Triggers timeout events which stored in database and clean them afterwards';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()->triggerLostEvents();
    }

}
