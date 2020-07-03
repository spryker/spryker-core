<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderByCriteriaPluginInterface;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;

/**
 * @method \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface getClient()
 */
class ProductViewOfferExpanderPlugin extends AbstractPlugin implements ProductViewExpanderByCriteriaPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the transfer object with the product offer reference according to provided criteria.
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        $localeName,
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer = null
    ): ProductViewTransfer {
        if (!$productStorageCriteriaTransfer) {
            return $productViewTransfer;
        }

        if (!$productViewTransfer->getIdProductConcrete()) {
            return $productViewTransfer;
        }

        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())->fromArray(
            $productStorageCriteriaTransfer->modifiedToArray(),
            true
        );
        $productOfferStorageCriteriaTransfer->fromArray($productViewTransfer->toArray(), true);

        $selectedAttributes = $productViewTransfer->getSelectedAttributes();
        if (isset($selectedAttributes[MerchantProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE])) {
            $productOfferStorageCriteriaTransfer->setProductOfferReference($selectedAttributes[MerchantProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE]);
        }
        $productOfferStorageCriteriaTransfer->addProductConcreteSku($productViewTransfer->getSku());

        return $productViewTransfer->setProductOfferReference(
            $this->getClient()->findProductConcreteDefaultProductOffer($productOfferStorageCriteriaTransfer)
        );
    }
}
