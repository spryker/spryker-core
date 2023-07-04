<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business\Reader;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferFacadeInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferFacadeInterface
     */
    protected ProductOfferServicePointStorageToProductOfferFacadeInterface $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(ProductOfferServicePointStorageToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param list<int> $productOfferIds
     *
     * @return list<string>
     */
    public function getProductOfferReferencesByProductOfferIds(array $productOfferIds): array
    {
        /** @var list<int> $productOfferIds */
        $productOfferIds = array_filter($productOfferIds);
        if (!$productOfferIds) {
            return [];
        }

        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())->setProductOfferConditions(
            (new ProductOfferConditionsTransfer())->setProductOfferIds($productOfferIds),
        );

        $productOfferCollectionTransfer = $this->productOfferFacade->getProductOfferCollection($productOfferCriteriaTransfer);
        if (!count($productOfferCollectionTransfer->getProductOffers())) {
            return [];
        }

        return $this->extractProductOfferReferencesFromProductOfferCollectionTransfer($productOfferCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractProductOfferReferencesFromProductOfferCollectionTransfer(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $productOfferReferences = [];
        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferReferences[] = $productOfferTransfer->getProductOfferReferenceOrFail();
        }

        return $productOfferReferences;
    }
}
