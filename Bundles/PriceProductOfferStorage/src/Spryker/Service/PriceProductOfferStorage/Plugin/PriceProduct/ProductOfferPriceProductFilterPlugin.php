<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductOfferStorage\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;

/**
 * @method \Spryker\Service\PriceProductOfferStorage\PriceProductOfferStorageConfig getConfig()
 */
class ProductOfferPriceProductFilterPlugin extends AbstractPlugin implements PriceProductFilterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array
     */
    public function filter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        if (!$priceProductFilterTransfer->getProductOffer()) {
            return $priceProductTransfers;
        }

        $priceProductTransfers = array_filter($priceProductTransfers, function (PriceProductTransfer $priceProductTransfer) use ($priceProductFilterTransfer) {
            return !($priceProductTransfer->getPriceDimension()->getProductOfferReference() && $priceProductTransfer->getPriceDimension()->getProductOfferReference() !== $priceProductFilterTransfer->getProductOffer()->getProductOfferReference());
        });

        $selectedOfferHasPrice = false;
        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($priceProductTransfer->getPriceDimension()->getProductOfferReference()) {
                $selectedOfferHasPrice = true;

                break;
            }
        }

        $priceProductTransfers = array_filter($priceProductTransfers, function (PriceProductTransfer $priceProductTransfer) use ($selectedOfferHasPrice) {
            if ($selectedOfferHasPrice) {
                return $priceProductTransfer->getPriceDimension()->getProductOfferReference();
            }

            return true;
        });

        return $priceProductTransfers;
    }

    /**
     * @return string
     */
    public function getDimensionName(): string
    {
        return PriceProductOfferStorageConfig::DIMENSION_TYPE_PRODUCT_OFFER;
    }
}
