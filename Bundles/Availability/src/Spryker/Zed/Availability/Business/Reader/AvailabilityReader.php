<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Reader;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

class AvailabilityReader implements AvailabilityReaderInterface
{
    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface
     */
    protected $availabilityRepository;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface $availabilityRepository
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        AvailabilityRepositoryInterface $availabilityRepository,
        AvailabilityToStoreFacadeInterface $storeFacade
    ) {
        $this->availabilityRepository = $availabilityRepository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function filterAvailableProducts(array $productConcreteTransfers): array
    {
        if (!$productConcreteTransfers) {
            return [];
        }

        $storeTransfer = $this->storeFacade->getCurrentStore();
        $productConcreteIds = $this->extractProductConcreteIdsFromProductConcreteTransfers($productConcreteTransfers);

        $mappedProductConcreteAvailabilityTransfers = $this->availabilityRepository
            ->getMappedProductConcreteAvailabilitiesByProductConcreteIds($productConcreteIds, $storeTransfer);

        return $this->getEligibleProductConcreteTransfers($productConcreteTransfers, $mappedProductConcreteAvailabilityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[] $mappedProductConcreteAvailabilityTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getEligibleProductConcreteTransfers(
        array $productConcreteTransfers,
        array $mappedProductConcreteAvailabilityTransfers
    ): array {
        $eligibleProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteAvailabilityTransfer = $mappedProductConcreteAvailabilityTransfers[$productConcreteTransfer->getIdProductConcrete()] ?? null;

            if (!$productConcreteAvailabilityTransfer) {
                continue;
            }

            if (!$this->isProductConcreteAvailable($productConcreteAvailabilityTransfer)) {
                continue;
            }

            $eligibleProductConcreteTransfers[] = $productConcreteTransfer;
        }

        return $eligibleProductConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return int[]
     */
    protected function extractProductConcreteIdsFromProductConcreteTransfers(array $productConcreteTransfers): array
    {
        $productConcreteIds = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteIds[] = $productConcreteTransfer->requireIdProductConcrete()->getIdProductConcrete();
        }

        return $productConcreteIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return bool
     */
    protected function isProductConcreteAvailable(ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer): bool
    {
        $isProductConcreteAvailable = $productConcreteAvailabilityTransfer->getAvailability() !== null
            && $productConcreteAvailabilityTransfer->getAvailability()->greaterThan(0);

        $isNeverOutOfStock = $productConcreteAvailabilityTransfer->getIsNeverOutOfStock();

        return $isProductConcreteAvailable || $isNeverOutOfStock;
    }
}
