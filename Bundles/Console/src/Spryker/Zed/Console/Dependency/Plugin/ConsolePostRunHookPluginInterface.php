<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Dependency\Plugin;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Use `\Spryker\Shared\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface` instead
 */
interface ConsolePostRunHookPluginInterface
{
    /**
     * Specification
     *  - The post-run plugins will be executed after each console commands
     *
     * @api
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function postRun(InputInterface $input, OutputInterface $output);
}
