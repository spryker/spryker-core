<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\SellableItemBatchRequestTransfer;
use Generated\Shared\Transfer\SellableItemBatchResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

class AvailabilityCartConnectorToAvailabilityBridge implements AvailabilityCartConnectorToAvailabilityInterface
{
    /**
     * @var \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct($availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findOrCreateProductConcreteAvailabilityBySkuForStore(
        string $sku,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer = null
    ): ?ProductConcreteAvailabilityTransfer {
        return $this->availabilityFacade->findOrCreateProductConcreteAvailabilityBySkuForStore($sku, $storeTransfer, $productAvailabilityCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemBatchResponseTransfer
     */
    public function areProductsSellableForStore(
        SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
    ): SellableItemBatchResponseTransfer {
        return $this->availabilityFacade->areProductsSellableForStore($sellableItemBatchRequestTransfer);
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore(
        string $sku,
        Decimal $quantity,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer = null
    ): bool {
        return $this->availabilityFacade->isProductSellableForStore($sku, $quantity, $storeTransfer, $productAvailabilityCriteriaTransfer);
    }
}
