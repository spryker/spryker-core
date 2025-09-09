<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchant\Communication\Console;

use Exception;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\DataImportMerchant\Business\DataImportMerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\DataImportMerchant\Business\DataImportMerchantBusinessFactory getBusinessFactory()
 */
class DataImportMerchantImportConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'data-import-merchant:import';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Imports data from files uploaded from Merchant Portal');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->getFacade()->import();
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
