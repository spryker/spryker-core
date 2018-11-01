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
class PriceProductMerchantRelationshipDeleteConsole extends Console
{
    public const COMMAND_NAME = 'price-product-merchant-relationship:delete';
    public const COMMAND_DESCRIPTION = 'Will delete all connections between product prices and merchant relations.';

    public const ARGUMENT_MERCHANT_RELATIONSHIP_ID = 'merchant-relationship-id';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption(static::ARGUMENT_MERCHANT_RELATIONSHIP_ID, 'm', InputArgument::OPTIONAL);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $idMerchantRelationship = $input->getOption(static::ARGUMENT_MERCHANT_RELATIONSHIP_ID);
        if ($idMerchantRelationship !== null) {
            $this->getFacade()
                ->deletePriceProductMerchantRelationshipByIdMerchantRelationship((int)$idMerchantRelationship);

            return static::CODE_SUCCESS;
        }

        $this->getFacade()->deleteAllPriceProductMerchantRelationship();

        return static::CODE_SUCCESS;
    }
}
