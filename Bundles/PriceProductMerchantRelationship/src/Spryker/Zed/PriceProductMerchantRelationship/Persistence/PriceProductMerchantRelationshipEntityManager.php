<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Persistence;

use Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Kernel\Transfer\EntityTransferInterface;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipPersistenceFactory getFactory()
 */
class PriceProductMerchantRelationshipEntityManager extends AbstractEntityManager implements PriceProductMerchantRelationshipEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer $priceProductMerchantRelationshipEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface
     */
    public function saveEntity(
        SpyPriceProductMerchantRelationshipEntityTransfer $priceProductMerchantRelationshipEntityTransfer
    ): EntityTransferInterface {
        if ($priceProductMerchantRelationshipEntityTransfer->getIdPriceProductMerchantRelationship()) {
            return $this->updatePriceProductMerchantRelationshipEntity($priceProductMerchantRelationshipEntityTransfer);
        }

        return $this->createPriceProductMerchantRelationshipEntity($priceProductMerchantRelationshipEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer $priceProductMerchantRelationshipEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer
     */
    protected function createPriceProductMerchantRelationshipEntity(
        SpyPriceProductMerchantRelationshipEntityTransfer $priceProductMerchantRelationshipEntityTransfer
    ): SpyPriceProductMerchantRelationshipEntityTransfer {
        $priceProductMerchantRelationshipEntity = new SpyPriceProductMerchantRelationship();
        $priceProductMerchantRelationshipEntity->fromArray($priceProductMerchantRelationshipEntityTransfer->toArray());
        $priceProductMerchantRelationshipEntity->save();

        $priceProductMerchantRelationshipEntityTransfer->setIdPriceProductMerchantRelationship(
            $priceProductMerchantRelationshipEntity->getIdPriceProductMerchantRelationship()
        );

        return $priceProductMerchantRelationshipEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer $priceProductMerchantRelationshipEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer
     */
    protected function updatePriceProductMerchantRelationshipEntity(
        SpyPriceProductMerchantRelationshipEntityTransfer $priceProductMerchantRelationshipEntityTransfer
    ): SpyPriceProductMerchantRelationshipEntityTransfer {
        $priceProductMerchantRelationshipEntity = $this->getFactory()
            ->createPriceProductMerchantRelationshipQuery()
            ->filterByIdPriceProductMerchantRelationship(
                $priceProductMerchantRelationshipEntityTransfer->getIdPriceProductMerchantRelationship()
            )
            ->findOne();

        if ($priceProductMerchantRelationshipEntity === null) {
            return $priceProductMerchantRelationshipEntityTransfer;
        }

        $priceProductMerchantRelationshipEntity->fromArray($priceProductMerchantRelationshipEntityTransfer->toArray());
        $priceProductMerchantRelationshipEntity->save();

        $priceProductMerchantRelationshipEntityTransfer->setIdPriceProductMerchantRelationship(
            $priceProductMerchantRelationshipEntity->getIdPriceProductMerchantRelationship()
        );

        return $priceProductMerchantRelationshipEntityTransfer;
    }

    /**
     * @param int $idPriceProductStore
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function deleteByIdPriceProductStoreAndIdMerchantRelationship(
        int $idPriceProductStore,
        int $idMerchantRelationship
    ): void {
        $priceProductMerchantRelationshipEnitity = $this->getFactory()->createPriceProductMerchantRelationshipQuery()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->filterByFkPriceProductStore($idPriceProductStore)
            ->findOne();

        if ($priceProductMerchantRelationshipEnitity) {
            $priceProductMerchantRelationshipEnitity->delete();
        }
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function deleteByIdMerchantRelationship(int $idMerchantRelationship): void
    {
        $priceProductMerchantRelationshipEntities = $this->getFactory()
            ->createPriceProductMerchantRelationshipQuery()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->find();

        $this->deleteEntitiesAndTriggerEvents($priceProductMerchantRelationshipEntities);
    }

    /**
     * @param int $idProductStore
     *
     * @return void
     */
    public function deleteByIdPriceProductStore(int $idProductStore): void
    {
        $priceProductMerchantRelationshipEntities = $this->getFactory()
            ->createPriceProductMerchantRelationshipQuery()
            ->filterByFkPriceProductStore($idProductStore)
            ->find();

        $this->deleteEntitiesAndTriggerEvents($priceProductMerchantRelationshipEntities);
    }

    /**
     * @return void
     */
    public function deleteAll(): void
    {
        $priceProductMerchantRelationshipEntities = $this->getFactory()
            ->createPriceProductMerchantRelationshipQuery()
            ->find();

        $this->deleteEntitiesAndTriggerEvents($priceProductMerchantRelationshipEntities);
    }

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship[]|\Propel\Runtime\Collection\ObjectCollection $priceProductMerchantRelationshipEntities
     *
     * @return void
     */
    protected function deleteEntitiesAndTriggerEvents(ObjectCollection $priceProductMerchantRelationshipEntities): void
    {
        foreach ($priceProductMerchantRelationshipEntities as $priceProductMerchantRelationshipEntity) {
            $priceProductMerchantRelationshipEntity->delete();
        }
    }
}
