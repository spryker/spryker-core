<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Persistence;

use Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer;
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
     * @param \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer $spyPriceProductMerchantRelationshipEntityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\EntityTransferInterface
     */
    public function saveEntity(
        SpyPriceProductMerchantRelationshipEntityTransfer $spyPriceProductMerchantRelationshipEntityTransfer
    ): EntityTransferInterface {
        $entity = $this->getFactory()->createPriceProductMerchantRelationshipQuery()
            ->filterByFkMerchantRelationship($spyPriceProductMerchantRelationshipEntityTransfer->getFkMerchantRelationship())
            ->filterByFkPriceProductStore($spyPriceProductMerchantRelationshipEntityTransfer->getFkPriceProductStore())
            ->filterByFkProductAbstract($spyPriceProductMerchantRelationshipEntityTransfer->getFkProductAbstract())
            ->filterByFkProduct($spyPriceProductMerchantRelationshipEntityTransfer->getFkProduct())
            ->findOneOrCreate();

        $entity->save();

        return $spyPriceProductMerchantRelationshipEntityTransfer;
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
     * @param \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship[] $priceProductMerchantRelationshipEntities
     *
     * @return void
     */
    protected function deleteEntitiesAndTriggerEvents($priceProductMerchantRelationshipEntities): void
    {
        foreach ($priceProductMerchantRelationshipEntities as $priceProductMerchantRelationshipEntity) {
            $priceProductMerchantRelationshipEntity->delete();
        }
    }
}
