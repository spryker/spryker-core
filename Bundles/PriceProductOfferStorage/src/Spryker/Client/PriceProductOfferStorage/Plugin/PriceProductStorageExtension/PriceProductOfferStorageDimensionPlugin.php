<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Plugin\PriceProductStorageExtension;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;

/**
 * @method \Spryker\Client\PriceProductOfferStorage\PriceProductOfferStorageClientInterface getClient()
 */
class PriceProductOfferStorageDimensionPlugin extends AbstractPlugin implements PriceProductStoragePriceDimensionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns offer prices data from Storage for concrete product.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices(int $idProductConcrete): array
    {
        return $this->getClient()->getProductOfferPrices($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function findProductAbstractPrices(int $idProductAbstract): array
    {
        return [];
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
        return PriceProductOfferStorageConfig::DIMENSION_TYPE_PRODUCT_OFFER;
    }
}
