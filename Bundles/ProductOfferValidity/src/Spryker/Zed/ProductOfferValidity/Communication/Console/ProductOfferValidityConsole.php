<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\ProductOfferValidity\Business\ProductOfferValidityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferValidity\Persistence\ProductOfferValidityRepositoryInterface getRepository()
 */
class ProductOfferValidityConsole extends Console
{
    public const COMMAND_NAME = 'product-offer:check-validity';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription('Updates product offers\' activity based on validity date ranges.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->updateProductOfferStatusByValidityDate();

        return static::CODE_SUCCESS;
    }
}
