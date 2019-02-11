<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageDatabase\Helper;

use Codeception\Module;
use Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorageQuery;
use Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacadeInterface;
use Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface;
use Spryker\Zed\ProductQuantityStorage\Business\ProductQuantityStorageFacadeInterface;
use SprykerTest\Shared\Availability\Helper\AvailabilityDataHelper;
use SprykerTest\Shared\Product\Helper\ProductDataHelper;
use SprykerTest\Shared\ProductQuantity\Helper\ProductQuantityDataHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class StorageDatabaseDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    protected const NAMESPACE_ROOT = '\\';

    /**
     * @param int $productConcreteId
     *
     * @return string
     */
    public function haveProductQuantityStorage(int $productConcreteId): string
    {
        $this->getProductQuantityDataHelper()->haveProductQuantity($productConcreteId);
        $this->getProductQuantityStorageFacade()->publishProductQuantity([$productConcreteId]);

        $productQuantityStorageEntity = SpyProductQuantityStorageQuery::create()
            ->filterByFkProduct($productConcreteId)
            ->findOne();

//        $this->getDataCleanupHelper()->_addCleanup(function () use ($productQuantityStorageEntity) {
//            $productQuantityStorageEntity->delete();
//        });

        return $productQuantityStorageEntity->getKey();
    }

    /**
     * @param int $availabilityAbstractId
     *
     * @return string
     */
    public function haveAvailabilityStorage(int $availabilityAbstractId): string
    {
        $this->getAvailabilityStorageFacade()->publish([$availabilityAbstractId]);

        $availabilityStorageEntity = $this->getAvailabilityStorageQueryContainer()
            ->queryAvailabilityStorageByAvailabilityAbstractIds([$availabilityAbstractId])
            ->findOne();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($availabilityStorageEntity) {
            $availabilityStorageEntity->delete();
        });

        return $availabilityStorageEntity->getKey();
    }

    /**
     * @return \SprykerTest\Shared\ProductQuantity\Helper\ProductQuantityDataHelper
     */
    protected function getProductQuantityDataHelper(): Module
    {
        return $this->getModule(static::NAMESPACE_ROOT . ProductQuantityDataHelper::class);
    }

    /**
     * @return \SprykerTest\Shared\Product\Helper\ProductDataHelper
     */
    protected function getProductDataHelper(): Module
    {
        return $this->getModule(static::NAMESPACE_ROOT . ProductDataHelper::class);
    }

    /**
     * @return \SprykerTest\Shared\Availability\Helper\AvailabilityDataHelper
     */
    protected function getAvailabilityDataHelper(): Module
    {
        return $this->getModule(static::NAMESPACE_ROOT . AvailabilityDataHelper::class);
    }

    /**
     * @return \Spryker\Zed\ProductQuantityStorage\Business\ProductQuantityStorageFacadeInterface
     */
    protected function getProductQuantityStorageFacade(): ProductQuantityStorageFacadeInterface
    {
        return $this->getLocator()->productQuantityStorage()->facade();
    }

    /**
     * @return \Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacadeInterface
     */
    protected function getAvailabilityStorageFacade(): AvailabilityStorageFacadeInterface
    {
        return $this->getLocator()->availabilityStorage()->facade();
    }

    /**
     * @return \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface
     */
    protected function getAvailabilityStorageQueryContainer(): AvailabilityStorageQueryContainerInterface
    {
        return $this->getLocator()->availabilityStorage()->queryContainer();
    }
}
