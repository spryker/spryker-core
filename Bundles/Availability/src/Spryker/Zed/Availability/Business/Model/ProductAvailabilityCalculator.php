<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

class ProductAvailabilityCalculator implements ProductAvailabilityCalculatorInterface
{
    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface
     */
    protected $availabilityRepository;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface $stockFacade
     */
    public function __construct(
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityToOmsFacadeInterface $omsFacade,
        AvailabilityToStockFacadeInterface $stockFacade
    ) {
        $this->availabilityRepository = $availabilityRepository;
        $this->omsFacade = $omsFacade;
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateAvailabilityForProductConcrete(string $concreteSku, StoreTransfer $storeTransfer): Decimal
    {
        $physicalItems = $this->stockFacade->calculateProductStockForStore($concreteSku, $storeTransfer);
        $reservedItems = $this->omsFacade->getOmsReservedProductQuantityForSku($concreteSku, $storeTransfer);

        return $this->normalizeQuantity($physicalItems->subtract($reservedItems));
    }

    /**
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function normalizeQuantity(Decimal $quantity): Decimal
    {
        return $quantity->greatherThanOrEquals(0) ? $quantity : new Decimal(0);
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function getCalculatedProductConcreteAvailabilityTransfer(string $concreteSku, StoreTransfer $storeTransfer): ProductConcreteAvailabilityTransfer
    {
        return (new ProductConcreteAvailabilityTransfer())
            ->setSku($concreteSku)
            ->setAvailability($this->calculateAvailabilityForProductConcrete($concreteSku, $storeTransfer))
            ->setIsNeverOutOfStock($this->stockFacade->isNeverOutOfStockForStore($concreteSku, $storeTransfer));
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function getCalculatedProductAbstractAvailabilityTransfer(string $abstractSku, StoreTransfer $storeTransfer): ProductAbstractAvailabilityTransfer
    {
        return $this->availabilityRepository
            ->getCalculatedProductAbstractAvailabilityBySkuAndStore(
                $abstractSku,
                $storeTransfer
            );
    }
}
