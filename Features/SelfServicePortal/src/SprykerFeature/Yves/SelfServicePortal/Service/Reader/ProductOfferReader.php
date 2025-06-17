<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Reader;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface;
use SprykerFeature\Yves\SelfServicePortal\Service\Resolver\ShopContextResolverInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @param \Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface $productOfferStorageClient
     * @param \SprykerFeature\Yves\SelfServicePortal\Service\Resolver\ShopContextResolverInterface $shopContextResolver
     */
    public function __construct(
        protected ProductOfferStorageClientInterface $productOfferStorageClient,
        protected ShopContextResolverInterface $shopContextResolver
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function getProductOffers(ProductViewTransfer $productViewTransfer): array
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return [];
        }

        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->fromArray($this->shopContextResolver->resolve()->modifiedToArray(), true);

        /** @var string $sku */
        $sku = $productViewTransfer->getSku();
        $productOfferStorageCriteriaTransfer->addProductConcreteSku($sku);

        $productOfferStorageCollectionTransfer = $this->productOfferStorageClient->getProductOfferStoragesBySkus($productOfferStorageCriteriaTransfer);
        /** @var array<\Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOffers */
        $productOffers = $productOfferStorageCollectionTransfer->getProductOffers()->getArrayCopy();

        return $productOffers;
    }
}
