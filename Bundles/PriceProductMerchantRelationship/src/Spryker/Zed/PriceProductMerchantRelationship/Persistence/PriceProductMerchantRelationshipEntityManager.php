<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
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
        $entity = $this->getFactory()->createPriceProductMerchantRelationshipQuery()
            ->filterByFkMerchantRelationship($priceProductMerchantRelationshipEntityTransfer->getFkMerchantRelationship())
            ->filterByFkPriceProductStore($priceProductMerchantRelationshipEntityTransfer->getFkPriceProductStore())
            ->filterByFkProductAbstract($priceProductMerchantRelationshipEntityTransfer->getFkProductAbstract())
            ->filterByFkProduct($priceProductMerchantRelationshipEntityTransfer->getFkProduct())
            ->findOneOrCreate();

        $entity->save();

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
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function deletePriceProductMerchantRelationships(
        int $idMerchantRelationship,
        PriceProductTransfer $priceProductTransfer
    ): void {
        $query = $this->getFactory()
            ->createPriceProductMerchantRelationshipQuery()
            ->usePriceProductStoreQuery()
                ->filterByFkStore($priceProductTransfer->getMoneyValue()->getFkStore())
                ->filterByFkCurrency($priceProductTransfer->getMoneyValue()->getFkCurrency())
                ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->endUse()
            ->filterByFkMerchantRelationship($priceProductTransfer->getPriceDimension()->getIdMerchantRelationship());

        $this->addFilteringByProductToQuery($priceProductTransfer, $query);

        $entity = $query->findOne();

        if ($entity) {
            $entity->delete();
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

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery $priceProductMerchantRelationshipQuery
     *
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery
     */
    protected function addFilteringByProductToQuery(PriceProductTransfer $priceProductTransfer, SpyPriceProductMerchantRelationshipQuery $priceProductMerchantRelationshipQuery): SpyPriceProductMerchantRelationshipQuery
    {
        if ($priceProductTransfer->getIdProduct()) {
            return $priceProductMerchantRelationshipQuery->filterByFkProduct($priceProductTransfer->getIdProduct());
        }

        return $priceProductMerchantRelationshipQuery->filterByFkProductAbstract($priceProductTransfer->getIdProductAbstract());
    }
}
