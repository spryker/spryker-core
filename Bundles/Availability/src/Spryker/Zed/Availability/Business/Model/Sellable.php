<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;

class Sellable implements SellableInterface
{
    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Availability\Business\Model\ProductAvailabilityReaderInterface
     */
    protected $productAvailabilityReader;

    /**
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface $stockFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Availability\Business\Model\ProductAvailabilityReaderInterface $productAvailabilityReader
     */
    public function __construct(
        AvailabilityToOmsFacadeInterface $omsFacade,
        AvailabilityToStockFacadeInterface $stockFacade,
        AvailabilityToStoreFacadeInterface $storeFacade,
        ProductAvailabilityReaderInterface $productAvailabilityReader
    ) {
        $this->omsFacade = $omsFacade;
        $this->stockFacade = $stockFacade;
        $this->storeFacade = $storeFacade;
        $this->productAvailabilityReader = $productAvailabilityReader;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return bool
     */
    public function isProductSellable(string $sku, Decimal $quantity): bool
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }

    /**
     * @param string $sku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateAvailabilityForProduct(string $sku): Decimal
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->calculateStock($sku, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductSellableForStore(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): bool
    {
        $productConcreteAvailabilityTransfer = $this->productAvailabilityReader
            ->findProductConcreteAvailability(
                (new ProductConcreteAvailabilityRequestTransfer())
                    ->setSku($sku)
                    ->setStore($storeTransfer)
            );

        if ($productConcreteAvailabilityTransfer === null) {
            return false;
        }

        return $productConcreteAvailabilityTransfer->getIsNeverOutOfStock() ||
            $productConcreteAvailabilityTransfer->getAvailability()->greatherThanOrEquals($quantity);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteAvailable(int $idProductConcrete): bool
    {
        $stockProductTransfers = $this->stockFacade->getStockProductsByIdProduct($idProductConcrete);

        foreach ($stockProductTransfers as $stockProductTransfer) {
            return $this->isProductSellable($stockProductTransfer->getSku(), new Decimal(1));
        }

        return false;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateAvailabilityForProductWithStore(string $sku, StoreTransfer $storeTransfer): Decimal
    {
        return $this->calculateStock($sku, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function calculateIsProductSellable(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): bool
    {
        if ($this->stockFacade->isNeverOutOfStockForStore($sku, $storeTransfer)) {
            return true;
        }

        $realStock = $this->calculateStock($sku, $storeTransfer);

        return $realStock->greatherThanOrEquals($quantity);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateStock($sku, StoreTransfer $storeTransfer): Decimal
    {
        $physicalItems = $this->stockFacade->calculateProductStockForStore($sku, $storeTransfer);
        $reservedItems = $this->omsFacade->getOmsReservedProductQuantityForSku($sku, $storeTransfer);

        return $physicalItems->subtract($reservedItems);
    }
}
