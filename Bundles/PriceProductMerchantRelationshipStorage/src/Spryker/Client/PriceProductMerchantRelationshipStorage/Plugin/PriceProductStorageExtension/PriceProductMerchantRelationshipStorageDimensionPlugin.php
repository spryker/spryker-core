<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Plugin\PriceProductStorageExtension;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface;

/**
 * @method \Spryker\Client\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageClientInterface getClient()
 * @method \Spryker\Client\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageDimensionPlugin extends AbstractPlugin implements PriceProductStoragePriceDimensionPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices(int $idProductConcrete): array
    {
        $idBusinessUnitFromCurrentCustomer = $this->getFactory()
            ->createCompanyBusinessUnitFinder()
            ->findCurrentCustomerCompanyBusinessUnitId();

        if (!$idBusinessUnitFromCurrentCustomer) {
            return [];
        }

        return $this->getFactory()
            ->createPriceProductMerchantRelationshipConcreteReader()
            ->findPriceMerchantRelationshipConcrete(
                $idProductConcrete,
                $idBusinessUnitFromCurrentCustomer
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPrices(int $idProductAbstract): array
    {
        $idBusinessUnitFromCurrentCustomer = $this->getFactory()
            ->createCompanyBusinessUnitFinder()
            ->findCurrentCustomerCompanyBusinessUnitId();

        if (!$idBusinessUnitFromCurrentCustomer) {
            return [];
        }

        return $this->getFactory()
            ->createPriceProductMerchantRelationshipAbstractReader()
            ->findProductAbstractPrices(
                $idProductAbstract,
                $idBusinessUnitFromCurrentCustomer
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string
    {
        return $this->getFactory()
            ->getPriceProductMerchantRelationshipStorageConfig()
            ->getPriceDimensionMerchantRelationship();
    }
}
