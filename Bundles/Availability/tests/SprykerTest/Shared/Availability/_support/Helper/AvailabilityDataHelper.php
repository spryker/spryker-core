<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Availability\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Availability\Persistence\SpyAvailabilityQuery;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AvailabilityDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @var int
     */
    protected const DEFAULT_QUANTITY = 10;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Spryker\DecimalObject\Decimal|null $quantity
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    public function haveAvailabilityAbstract(ProductConcreteTransfer $productConcreteTransfer, ?Decimal $quantity = null): SpyAvailabilityAbstract
    {
        $quantity = $quantity ?? new Decimal(static::DEFAULT_QUANTITY);
        $storeTransfer = $this->getStoreFacade()->getCurrentStore();

        $availabilityAbstractEntity = (new SpyAvailabilityAbstractQuery())
            ->filterByAbstractSku($productConcreteTransfer->getAbstractSku())
            ->filterByFkStore($storeTransfer->getIdStore())
            ->findOneOrCreate();

        $availabilityAbstractEntity
            ->setQuantity($quantity)
            ->save();

        (new SpyAvailabilityQuery())
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterBySku($productConcreteTransfer->getSku())
            ->filterByFkAvailabilityAbstract($availabilityAbstractEntity->getIdAvailabilityAbstract())
            ->findOneOrCreate()
            ->setQuantity($quantity)
            ->save();

        return $availabilityAbstractEntity;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     * @param string|float|int|null $quantity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function haveAvailabilityConcrete(string $sku, ?StoreTransfer $storeTransfer = null, $quantity = null): ProductConcreteAvailabilityTransfer
    {
        $storeTransfer = $storeTransfer ?? $this->getStoreFacade()->getCurrentStore();

        $this->getAvailabilityFacade()->saveProductAvailabilityForStore(
            $sku,
            $quantity === null ? new Decimal(static::DEFAULT_QUANTITY) : new Decimal($quantity),
            $storeTransfer,
        );

        $productConcreteAvailabilityTransfer = $this->getAvailabilityFacade()->findOrCreateProductConcreteAvailabilityBySkuForStore(
            $sku,
            $storeTransfer,
        );

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConcreteAvailabilityTransfer) {
            $availabilityEntity = SpyAvailabilityQuery::create()->findOneBySku($productConcreteAvailabilityTransfer->getSku());

            if ($availabilityEntity) {
                $availabilityEntity->delete();
            }
        });

        return $productConcreteAvailabilityTransfer;
    }

    /**
     * @return void
     */
    public function ensureAvailabilityTableIsEmpty(): void
    {
        SpyAvailabilityQuery::create()->deleteAll();
    }

    /**
     * @return \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    private function getAvailabilityFacade(): AvailabilityFacadeInterface
    {
        return $this->getLocator()->availability()->facade();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()->store()->facade();
    }
}
