<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrix\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\OrderMatrix\Business\OrderMatrixFacadeInterface getFacade()
 * @method \Spryker\Zed\OrderMatrix\Persistence\OrderMatrixRepositoryInterface getRepository()
 */
class OrderMatrixConsole extends Console
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'order-matrix:sync';

    /**
     * @var string
     */
    protected const DESCRIPTION = 'Synchronizes the order matrix and writes it to storage.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
             ->setDescription(static::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->writeOrderMatrix();
        $output->writeln('Order matrix has been synchronized.');

        return static::CODE_SUCCESS;
    }
}
