<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\AvailabilityStorage\Communication\AvailabilityStorageCommunicationFactory getFactory()
 */
class AbstractAvailabilityStorageListener extends AbstractPlugin
{
    const ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const ID_AVAILABILITY_ABSTRACT = 'id_availability_abstract';
    const FK_AVAILABILITY_ABSTRACT = 'fkAvailabilityAbstract';

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    protected function publish(array $availabilityIds)
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
    protected function unpublish(array $availabilityIds)
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
        $spyAvailabilityStorageEntity->save();
    }

    /**
     * @param array $availabilityIds
     *
     * @return array
     */
    protected function findAvailabilityAbstractEntities(array $availabilityIds)
    {
        return $this->getQueryContainer()->queryAvailabilityAbstractWithRelationsByIds($availabilityIds)->find()->getData();
    }

    /**
     * @param array $availabilityAbstractIds
     *
     * @return array
     */
    protected function findAvailabilityStorageEntitiesByAvailabilityAbstractIds(array $availabilityAbstractIds)
    {
        return $this->getQueryContainer()->queryAvailabilityStorageByAvailabilityAbstractIds($availabilityAbstractIds)->find()->toKeyIndex(static::FK_AVAILABILITY_ABSTRACT);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
