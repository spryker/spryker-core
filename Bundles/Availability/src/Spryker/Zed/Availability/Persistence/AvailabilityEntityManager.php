<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Spryker\Zed\Availability\Persistence\Exception\AvailabilityAbstractNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityPersistenceFactory getFactory()
 */
class AvailabilityEntityManager extends AbstractEntityManager implements AvailabilityEntityManagerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $abstractSku
     *
     * @return bool
     */
    public function saveProductConcreteAvailability(
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer,
        StoreTransfer $storeTransfer,
        string $abstractSku
    ): bool {
        $availabilityEntity = $this->prepareAvailabilityEntityForSave(
            $productConcreteAvailabilityTransfer,
            $storeTransfer,
            $abstractSku
        );

        $isAvailabilityChanged = $this->isAvailabilityChanged($availabilityEntity);

        $availabilityEntity->save();

        return $isAvailabilityChanged;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function saveProductAbstractAvailability(
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer,
        StoreTransfer $storeTransfer
    ): ProductAbstractAvailabilityTransfer {
        $productAbstractAvailabilityTransfer
            ->requireSku()
            ->requireAvailability();

        $availabilityAbstractEntity = $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByAbstractSku($productAbstractAvailabilityTransfer->getSku())
            ->filterByFkStore($storeTransfer->getIdStore())
            ->findOneOrCreate();

        $availabilityAbstractEntity
            ->setQuantity($productAbstractAvailabilityTransfer->getAvailability())
            ->save();

        return $productAbstractAvailabilityTransfer;
    }

    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability $availabilityEntity
     *
     * @return bool
     */
    protected function isAvailabilityChanged(SpyAvailability $availabilityEntity): bool
    {
        return $availabilityEntity->isColumnModified(SpyAvailabilityTableMap::COL_IS_NEVER_OUT_OF_STOCK) ||
            $availabilityEntity->isColumnModified(SpyAvailabilityTableMap::COL_QUANTITY);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $abstractSku
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function prepareAvailabilityEntityForSave(
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer,
        StoreTransfer $storeTransfer,
        string $abstractSku
    ): SpyAvailability {
        $availabilityEntity = $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterBySku($productConcreteAvailabilityTransfer->getSku())
            ->findOneOrCreate();

        if ($availabilityEntity->isNew()) {
            $availabilityAbstractEntity = $this->findAvailabilityAbstractEntity($abstractSku, $storeTransfer);
            $availabilityEntity->setFkAvailabilityAbstract($availabilityAbstractEntity->getIdAvailabilityAbstract());
        }

        $availabilityEntity->setQuantity($productConcreteAvailabilityTransfer->getAvailability());
        $availabilityEntity->setIsNeverOutOfStock($productConcreteAvailabilityTransfer->getIsNeverOutOfStock());

        return $availabilityEntity;
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @throws \Spryker\Zed\Availability\Persistence\Exception\AvailabilityAbstractNotFoundException
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    protected function findAvailabilityAbstractEntity(string $abstractSku, StoreTransfer $storeTransfer): SpyAvailabilityAbstract
    {
        $availabilityAbstractEntity = $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByAbstractSku($abstractSku)
            ->filterByFkStore($storeTransfer->getIdStore())
            ->findOne();

        if ($availabilityAbstractEntity === null) {
            throw new AvailabilityAbstractNotFoundException(
                'You cannot update concrete availability without updating abstract availability first'
            );
        }

        return $availabilityAbstractEntity;
    }
}
