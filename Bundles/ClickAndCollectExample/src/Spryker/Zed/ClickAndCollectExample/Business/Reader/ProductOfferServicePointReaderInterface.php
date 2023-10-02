<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\Reader;

use Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer;

interface ProductOfferServicePointReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function getPickupProductOfferServicePoints(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function getDeliveryProductOfferServicePoints(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array;
}
