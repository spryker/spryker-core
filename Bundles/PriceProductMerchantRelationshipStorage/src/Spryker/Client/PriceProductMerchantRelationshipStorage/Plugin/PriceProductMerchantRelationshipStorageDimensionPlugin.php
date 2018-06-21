<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceProductStorage\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface;

/**
 * @method \Spryker\Client\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageClientInterface getClient()
 * @method \Spryker\Client\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageDimensionPlugin extends AbstractPlugin implements PriceProductStoragePriceDimensionPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices(int $idProductConcrete): array
    {
        $idMerchantRelationship = $this->getFactory()
            ->createMerchantRelationshipFinder()
            ->findCurrentCustomerMerchantRelationshipId();

        if (!$idMerchantRelationship) {
            return null;
        }

        return $this->getFactory()
            ->createPriceProductMerchantRelationshipConcreteReader()
            ->findPriceMerchantRelationshipConcrete(
                $idProductConcrete,
                $idMerchantRelationship
            );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductAbstractPrices(int $idProductAbstract): array
    {
        $idMerchantRelationship = $this->getFactory()
            ->createMerchantRelationshipFinder()
            ->findCurrentCustomerMerchantRelationshipId();

        if (!$idMerchantRelationship) {
            return null;
        }

        return $this->getFactory()
            ->createPriceProductMerchantRelationshipAbstractReader()
            ->findPriceMerchantRelationshipAbstract(
                $idProductAbstract,
                $idMerchantRelationship
            );
    }
}
