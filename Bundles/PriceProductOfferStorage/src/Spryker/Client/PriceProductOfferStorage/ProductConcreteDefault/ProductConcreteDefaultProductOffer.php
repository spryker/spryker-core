<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\ProductConcreteDefault;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToMerchantProductOfferStorageClientInterface;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;

class ProductConcreteDefaultProductOffer implements ProductConcreteDefaultProductOfferInterface
{
    /**
     * @var \Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToMerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @param \Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     */
    public function __construct(PriceProductOfferStorageToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient)
    {
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
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
        $productOfferReferences = $this->merchantProductOfferStorageClient->getProductOfferReferences($productViewTransfer->getSku());

        if (!$productOfferReferences) {
            return null;
        }

        $selectedAttributes = $productViewTransfer->getSelectedAttributes();

        if (isset($selectedAttributes[PriceProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE])
            && in_array($selectedAttributes[PriceProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE], $productOfferReferences)
        ) {
            return $selectedAttributes[PriceProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE];
        }

        return reset($productOfferReferences);
    }
}
