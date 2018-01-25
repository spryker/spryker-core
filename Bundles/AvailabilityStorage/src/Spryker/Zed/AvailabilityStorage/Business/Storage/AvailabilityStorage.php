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
        $spyAvailabilityEntities = $this->findAvailabilityAbstractEntities($availabilityIds);
        $spyAvailabilityStorageEntities = $this->findAvailabilityStorageEntitiesByAvailabilityAbstractIds($availabilityIds);

        $this->storeData($spyAvailabilityEntities, $spyAvailabilityStorageEntities);
    }

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds)
    {
        $spyAvailabilityStorageEntities = $this->findAvailabilityStorageEntitiesByAvailabilityAbstractIds($availabilityIds);
        foreach ($spyAvailabilityStorageEntities as $spyAvailabilityStorageEntity) {
            $spyAvailabilityStorageEntity->delete();
        }
    }

    /**
     * @param array $spyAvailabilityEntities
     * @param array $spyAvailabilityStorageEntities
     *
     * @return void
     */
    protected function storeData(array $spyAvailabilityEntities, array $spyAvailabilityStorageEntities)
    {
        foreach ($spyAvailabilityEntities as $spyAvailability) {
            $idAvailability = $spyAvailability[static::ID_AVAILABILITY_ABSTRACT];
            if (isset($spyAvailabilityStorageEntities[$idAvailability])) {
                $this->storeDataSet($spyAvailability, $spyAvailabilityStorageEntities[$idAvailability]);
            } else {
                $this->storeDataSet($spyAvailability);
            }
        }
    }

    /**
     * @param array $spyAvailabilityEntity
     * @param \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage|null $spyAvailabilityStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $spyAvailabilityEntity, SpyAvailabilityStorage $spyAvailabilityStorageEntity = null)
    {
        if ($spyAvailabilityStorageEntity === null) {
            $spyAvailabilityStorageEntity = new SpyAvailabilityStorage();
        }
        $spyAvailabilityStorageEntity->setFkProductAbstract($spyAvailabilityEntity[static::ID_PRODUCT_ABSTRACT]);
        $spyAvailabilityStorageEntity->setFkAvailabilityAbstract($spyAvailabilityEntity[static::ID_AVAILABILITY_ABSTRACT]);
        $spyAvailabilityStorageEntity->setData($spyAvailabilityEntity);
        $spyAvailabilityStorageEntity->setStore($this->getStoreName());
        $spyAvailabilityStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyAvailabilityStorageEntity->save();
    }

    /**
     * @param array $availabilityIds
     *
     * @return array
     */
    protected function findAvailabilityAbstractEntities(array $availabilityIds)
    {
        return $this->queryContainer->queryAvailabilityAbstractWithRelationsByIds($availabilityIds)->find()->getData();
    }

    /**
     * @param array $availabilityAbstractIds
     *
     * @return \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage[]
     */
    protected function findAvailabilityStorageEntitiesByAvailabilityAbstractIds(array $availabilityAbstractIds)
    {
        return $this->queryContainer->queryAvailabilityStorageByAvailabilityAbstractIds($availabilityAbstractIds)->find()->toKeyIndex(static::FK_AVAILABILITY_ABSTRACT);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->store->getStoreName();
    }
}
