<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface getRepository()
 */
class ProductLabelValidityConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'product-label:validity';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('(Un)Publish product labels based on validity date ranges');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->checkLabelValidityDateRangeAndTouch();

        return static::CODE_SUCCESS;
    }
}
