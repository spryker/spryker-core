<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventBehavior\Communication\Plugin\Console;

use Propel\Runtime\Propel;
use Spryker\Zed\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface getFacade()
 */
class EventBehaviorPostHookPlugin extends AbstractPlugin implements ConsolePostRunHookPluginInterface
{

    const CODE_SUCCESS = 0;
    const CODE_ERROR = 1;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function postRun(InputInterface $input, OutputInterface $output)
    {
        $defaultDataSourceName = Propel::getServiceContainer()->getDefaultDatasource();
        if (!Propel::getServiceContainer()->hasConnectionManager($defaultDataSourceName)) {
            return static::CODE_ERROR;
        }

        $this->getFacade()->triggerRuntimeEvents();

        return static::CODE_SUCCESS;
    }

}
