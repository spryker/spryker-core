<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\ProductValidity\Business\ProductValidityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface getQueryContainer()
 */
class ProductValidityConsole extends Console
{
    public const COMMAND_NAME = 'product:check-validity';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('(Un)Publish products based on validity date ranges.');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()->checkProductValidityDateRangeAndTouch();
    }
}
