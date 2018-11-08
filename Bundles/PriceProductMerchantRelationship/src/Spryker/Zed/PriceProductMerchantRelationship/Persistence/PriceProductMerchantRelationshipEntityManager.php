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
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer $priceProductMerchantRelationshipEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface
     */
    public function saveEntity(
        SpyPriceProductMerchantRelationshipEntityTransfer $priceProductMerchantRelationshipEntityTransfer
    ): EntityTransferInterface {
        if (!$priceProductMerchantRelationshipEntityTransfer->getIdPriceProductMerchantRelationship()) {
            $entity = new SpyPriceProductMerchantRelationship();
            $entity->fromArray($priceProductMerchantRelationshipEntityTransfer->toArray());
            $entity->save();

            return $priceProductMerchantRelationshipEntityTransfer;
        }

        $this->updatePriceProductMerchantRelationshipEntity($priceProductMerchantRelationshipEntityTransfer);

        return $priceProductMerchantRelationshipEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer $priceProductMerchantRelationshipEntityTransfer
     *
     * @return void
     */
    protected function updatePriceProductMerchantRelationshipEntity(
        SpyPriceProductMerchantRelationshipEntityTransfer $priceProductMerchantRelationshipEntityTransfer
    ): void {
        $priceProductMerchantRelationshipEntity = $this->getFactory()
            ->createPriceProductMerchantRelationshipQuery()
            ->filterByIdPriceProductMerchantRelationship(
                $priceProductMerchantRelationshipEntityTransfer->getIdPriceProductMerchantRelationship()
            )
            ->findOne();

        if ($priceProductMerchantRelationshipEntity === null) {
            return;
        }

        $priceProductMerchantRelationshipEntity->fromArray($priceProductMerchantRelationshipEntityTransfer->toArray());
        $priceProductMerchantRelationshipEntity->save();
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
