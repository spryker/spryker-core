<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Persistence;

use Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer;
use Spryker\Shared\Kernel\Transfer\EntityTransferInterface;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Traversable;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipPersistenceFactory getFactory()
 */
class PriceProductMerchantRelationshipEntityManager extends AbstractEntityManager implements PriceProductMerchantRelationshipEntityManagerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer $SpyPriceProductMerchantRelationshipEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface
     */
    public function saveEntity(
        SpyPriceProductMerchantRelationshipEntityTransfer $SpyPriceProductMerchantRelationshipEntityTransfer
    ): EntityTransferInterface {
        $entity = $this->getFactory()->createPriceProductMerchantRelationshipQuery()
            ->filterByFkCompanyBusinessUnit($SpyPriceProductMerchantRelationshipEntityTransfer->getFkCompanyBusinessUnit())
            ->filterByFkPriceProductStore($SpyPriceProductMerchantRelationshipEntityTransfer->getFkPriceProductStore())
            ->filterByFkProductAbstract($SpyPriceProductMerchantRelationshipEntityTransfer->getFkProductAbstract())
            ->filterByFkProduct($SpyPriceProductMerchantRelationshipEntityTransfer->getFkProduct())
            ->findOneOrCreate();

        $entity->save();

        return $SpyPriceProductMerchantRelationshipEntityTransfer;
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

        $priceProductBusinessUnitEnitity = $this->getFactory()->createPriceProductMerchantRelationshipQuery()
            ->filterByFkCompanyBusinessUnit($idMerchantRelationship)
            ->filterByFkPriceProductStore($idPriceProductStore)
            ->findOne();

        if ($priceProductBusinessUnitEnitity) {
            $priceProductBusinessUnitEnitity->delete();
        }
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function deleteByIdMerchantRelationship(int $idMerchantRelationship): void
    {

        $priceProductBusinessUnitEntities = $this->getFactory()
            ->createPriceProductMerchantRelationshipQuery()
            ->filterByFkCompanyBusinessUnit($idMerchantRelationship)
            ->find();

        $this->deleteEntitiesAndTriggerEvents($priceProductBusinessUnitEntities);
    }

    /**
     * @return void
     */
    public function deleteAll(): void
    {
        $priceProductBusinessUnitEntities = $this->getFactory()
            ->createPriceProductMerchantRelationshipQuery()
            ->find();

        $this->deleteEntitiesAndTriggerEvents($priceProductBusinessUnitEntities);
    }

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship[] $priceProductBusinessUnitEntities
     *
     * @return void
     */
    protected function deleteEntitiesAndTriggerEvents(Traversable $priceProductBusinessUnitEntities): void
    {
        foreach ($priceProductBusinessUnitEntities as $priceProductBusinessUnitEntity) {
            $priceProductBusinessUnitEntity->delete();
        }
    }
}
