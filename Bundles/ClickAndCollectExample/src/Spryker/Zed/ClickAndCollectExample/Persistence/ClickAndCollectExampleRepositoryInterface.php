<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Persistence;

use Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer;

interface ClickAndCollectExampleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function getPickupProductOfferServicePointsByCriteria(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function getDeliveryProductOfferServicePointsByCriteria(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferStockTransfer>
     */
    public function getProductOfferStocksByCriteria(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferPriceTransfer>
     */
    public function getProductOfferPricesByCriteria(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array;
}
