<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Business\Storage;

use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface;

class AvailabilityStorage implements AvailabilityStorageInterface
{
    const ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const ID_AVAILABILITY_ABSTRACT = 'id_availability_abstract';
    const FK_AVAILABILITY_ABSTRACT = 'fkAvailabilityAbstract';
    const STORE = 'Store';
    const STORE_NAME = 'name';

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(Store $store, AvailabilityStorageQueryContainerInterface $queryContainer, $isSendingToQueue)
    {
        $this->store = $store;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function publish(array $availabilityIds)
    {
        $availabilityEntitityCollection = $this->findAvailabilityAbstractEntities($availabilityIds);
        $availabilityStorageEntityCollection = $this->findAvailabilityStorageEntitiesByAvailabilityAbstractIds($availabilityIds);

        $this->storeData($availabilityEntitityCollection, $availabilityStorageEntityCollection);
    }

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds)
    {
        $availabilityStorageEntityCollection = $this->findAvailabilityStorageEntitiesByAvailabilityAbstractIds($availabilityIds);
        foreach ($availabilityStorageEntityCollection as $availabilityStorageEntity) {
            $availabilityStorageEntity->delete();
        }
    }

    /**
     * @param array $availabilityEntities
     * @param array $availabilityStorageEntityCollection
     *
     * @return void
     */
    protected function storeData(array $availabilityEntities, array $availabilityStorageEntityCollection)
    {
        foreach ($availabilityEntities as $availability) {
            $idAvailability = $availability[static::ID_AVAILABILITY_ABSTRACT];
            $storeName = $availability[static::STORE][static::STORE_NAME];

            if ($this->isExistingEntity($availabilityStorageEntityCollection, $idAvailability, $storeName)) {
                $this->storeDataSet($availability, $availabilityStorageEntityCollection[$idAvailability]);
            } else {
                $this->storeDataSet($availability);
            }
        }
    }

    /**
     * @param array $availability
     * @param \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage|null $availabilityStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $availability, ?SpyAvailabilityStorage $availabilityStorageEntity = null)
    {
        if ($availabilityStorageEntity === null) {
            $availabilityStorageEntity = new SpyAvailabilityStorage();
        }

        $storeName = $availability[static::STORE][static::STORE_NAME];
        $availabilityStorageEntity->setFkProductAbstract($availability[static::ID_PRODUCT_ABSTRACT])
            ->setFkAvailabilityAbstract($availability[static::ID_AVAILABILITY_ABSTRACT])
            ->setData($availability)
            ->setStore($storeName)
            ->setIsSendingToQueue($this->isSendingToQueue);

        $availabilityStorageEntity->save();
    }

    /**
     * @param array $availabilityIds
     *
     * @return array
     */
    protected function findAvailabilityAbstractEntities(array $availabilityIds)
    {
        return $this->queryContainer
            ->queryAvailabilityAbstractWithRelationsByIds($availabilityIds)
            ->find()
            ->getData();
    }

    /**
     * @param array $availabilityAbstractIds
     *
     * @return \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage[]
     */
    protected function findAvailabilityStorageEntitiesByAvailabilityAbstractIds(array $availabilityAbstractIds)
    {
        return $this->queryContainer
            ->queryAvailabilityStorageByAvailabilityAbstractIds($availabilityAbstractIds)
            ->find()
            ->toKeyIndex(static::FK_AVAILABILITY_ABSTRACT);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->store->getStoreName();
    }

    /**
     * @param array $availabilityStorageEntityCollection
     * @param int $idAvailability
     * @param string $storeName
     *
     * @return bool
     */
    protected function isExistingEntity(array $availabilityStorageEntityCollection, $idAvailability, $storeName)
    {
        return (isset($availabilityStorageEntityCollection[$idAvailability]) && $availabilityStorageEntityCollection[$idAvailability]->getStore() === $storeName);
    }
}
