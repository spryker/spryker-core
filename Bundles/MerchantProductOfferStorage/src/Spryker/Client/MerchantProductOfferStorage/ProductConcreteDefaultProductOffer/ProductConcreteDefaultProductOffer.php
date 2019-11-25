<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\MerchantProductOfferStorage\Plugin\ProductOfferProviderPluginInterface;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;

class ProductConcreteDefaultProductOffer implements ProductConcreteDefaultProductOfferInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface
     */
    protected $productOfferStorageReader;

    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Plugin\ProductOfferProviderPluginInterface
     */
    protected $defaultProductOfferPlugin;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface $productOfferStorageReader
     * @param \Spryker\Client\MerchantProductOfferStorage\Plugin\ProductOfferProviderPluginInterface $defaultProductOfferPlugin
     */
    public function __construct(ProductOfferStorageReaderInterface $productOfferStorageReader, ProductOfferProviderPluginInterface $defaultProductOfferPlugin)
    {
        $this->productOfferStorageReader = $productOfferStorageReader;
        $this->defaultProductOfferPlugin = $defaultProductOfferPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return string|null
     */
    public function findProductOfferReference(ProductViewTransfer $productViewTransfer): ?string
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return null;
        }
        $productOfferReferences = $this->productOfferStorageReader->getProductOfferReferences($productViewTransfer->getSku());

        if (!$productOfferReferences) {
            return null;
        }

        $selectedAttributes = $productViewTransfer->getSelectedAttributes();

        if (isset($selectedAttributes[MerchantProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE])
            && in_array($selectedAttributes[MerchantProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE], $productOfferReferences)
        ) {
            return $selectedAttributes[MerchantProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE];
        }

        return $this->defaultProductOfferPlugin->provideDefaultProductOfferReference($productOfferReferences);
    }
}
