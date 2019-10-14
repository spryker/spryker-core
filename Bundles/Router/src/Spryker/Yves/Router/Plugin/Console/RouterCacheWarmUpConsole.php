<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\Console;

use Spryker\Yves\Kernel\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Yves\Router\RouterFactory getFactory()
 */
class RouterCacheWarmUpConsole extends Console
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName('router:cache:warm-up')
            ->setDescription('Builds a fresh cache for the Yves router.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFactory()->createCache()->warmUp();

        return static::CODE_SUCCESS;
    }
}
