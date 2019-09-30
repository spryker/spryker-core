<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Availability;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface;

class ProductPackagingUnitAvailabilityHandler implements ProductPackagingUnitAvailabilityHandlerInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface
     */
    protected $packagingUnitReader;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface $packagingUnitReader
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductPackagingUnitReaderInterface $packagingUnitReader,
        ProductPackagingUnitToAvailabilityFacadeInterface $availabilityFacade,
        ProductPackagingUnitToStoreFacadeInterface $storeFacade
    ) {
        $this->packagingUnitReader = $packagingUnitReader;
        $this->availabilityFacade = $availabilityFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateProductPackagingUnitLeadProductAvailability(string $sku): void
    {
        $productPackagingLeadProductTransfer = $this->findProductPackagingLeadProductByProductPackagingSku($sku);

        if (!$productPackagingLeadProductTransfer) {
            return;
        }

        $this->updateAvailabilityForLeadProduct($productPackagingLeadProductTransfer->getProduct()->getSku());
    }

    /**
     * @param string $leadProductSku
     *
     * @return void
     */
    protected function updateAvailabilityForLeadProduct(string $leadProductSku): void
    {
        $currentStoreTransfer = $this->storeFacade->getCurrentStore();

        $stores = $currentStoreTransfer->getStoresWithSharedPersistence();
        $stores[] = $currentStoreTransfer->getName();

        foreach ($stores as $storeName) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);
            $stock = $this->availabilityFacade
                ->calculateAvailabilityForProductWithStore($leadProductSku, $storeTransfer);

            $this->availabilityFacade->saveProductAvailabilityForStore($leadProductSku, $stock, $storeTransfer);
        }
    }

    /**
     * @param string $productPackagingUnitSku
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    protected function findProductPackagingLeadProductByProductPackagingSku(string $productPackagingUnitSku): ?ProductPackagingLeadProductTransfer
    {
        return $this->packagingUnitReader
            ->findProductPackagingLeadProductByProductPackagingSku($productPackagingUnitSku);
    }
}
