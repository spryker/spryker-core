<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\SellableItemBatchRequestTransfer;
use Generated\Shared\Transfer\SellableItemBatchResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

interface SellableInterface
{
    /**
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemBatchResponseTransfer
     */
    public function areProductsSellableForStore(
        SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
    ): SellableItemBatchResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemsBatchRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemBatchResponseTransfer $sellableItemsBatchResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemBatchResponseTransfer
     */
    public function areProductConcretesSellableForStore(
        SellableItemBatchRequestTransfer $sellableItemsBatchRequestTransfer,
        SellableItemBatchResponseTransfer $sellableItemsBatchResponseTransfer
    ): SellableItemBatchResponseTransfer;

    /**
     * @param string $concreteSku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore(
        string $concreteSku,
        Decimal $quantity,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer = null
    ): bool;

    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteAvailable(int $idProductConcrete): bool;
}
