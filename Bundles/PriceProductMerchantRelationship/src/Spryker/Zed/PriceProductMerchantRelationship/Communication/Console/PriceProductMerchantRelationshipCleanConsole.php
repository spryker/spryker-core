<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Communication\PriceProductMerchantRelationshipCommunicationFactory getFactory()
 */
class PriceProductMerchantRelationshipCleanConsole extends Console
{
    public const COMMAND_NAME = 'price-product-business-unit:clean';
    public const COMMAND_DESCRIPTION = 'Will delete all connections between product prices and company business units.';

    public const ARGUMENT_BUSINESS_UNIT_ID = 'business-unit-id';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption(static::ARGUMENT_BUSINESS_UNIT_ID, 'b', InputArgument::OPTIONAL);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $idBusinessUnit = $input->getOption(static::ARGUMENT_BUSINESS_UNIT_ID);
        if ($idBusinessUnit !== null) {
            $this->getFacade()
                ->deletePriceProductBusinessUnitByIdBusinessUnit((int)$idBusinessUnit);

            return static::CODE_SUCCESS;
        }

        $this->getFacade()->deleteAllPriceProductBusinessUnit();

        return static::CODE_SUCCESS;
    }
}
