<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\Map\SpyPriceProductAbstractMerchantRelationshipStorageTableMap;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\Map\SpyPriceProductConcreteMerchantRelationshipStorageTableMap;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStoragePersistenceFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageEntityManager extends AbstractEntityManager implements PriceProductMerchantRelationshipStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function updatePriceProductAbstract(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
    ): void {
        $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->findOneByPriceKey($priceProductMerchantRelationshipStorageTransfer->getPriceKey())
            ->setData($this->formatData($priceProductMerchantRelationshipStorageTransfer))
            ->setIsSendingToQueue($this->getFactory()->getConfig()->isSendingToQueue())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function createPriceProductAbstract(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
    ): void {
        (new SpyPriceProductAbstractMerchantRelationshipStorage())
            ->setFkProductAbstract($priceProductMerchantRelationshipStorageTransfer->getIdProduct())
            ->setFkCompanyBusinessUnit($priceProductMerchantRelationshipStorageTransfer->getIdCompanyBusinessUnit())
            ->setPriceKey($priceProductMerchantRelationshipStorageTransfer->getPriceKey())
            ->setData($this->formatData($priceProductMerchantRelationshipStorageTransfer))
            ->setIsSendingToQueue($this->getFactory()->getConfig()->isSendingToQueue())
            ->save();
    }

    /**
     * @param string[] $priceKeys
     *
     * @return void
     */
    public function deletePriceProductAbstractsByPriceKeys(
        array $priceKeys
    ): void {
        if (empty($priceKeys)) {
            return;
        }

        $priceProductAbstractMerchantRelationshipStorageEntities = $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->filterByPriceKey_In($priceKeys)
            ->find();

        foreach ($priceProductAbstractMerchantRelationshipStorageEntities as $priceProductAbstractMerchantRelationshipStorageEntity) {
            $priceProductAbstractMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function updatePriceProductConcrete(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
    ): void {
        $this->getFactory()
            ->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->findOneByPriceKey($priceProductMerchantRelationshipStorageTransfer->getPriceKey())
            ->setData($this->formatData($priceProductMerchantRelationshipStorageTransfer))
            ->setIsSendingToQueue($this->getFactory()->getConfig()->isSendingToQueue())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function createPriceProductConcrete(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
    ): void {
        (new SpyPriceProductConcreteMerchantRelationshipStorage())
            ->setFkProduct($priceProductMerchantRelationshipStorageTransfer->getIdProduct())
            ->setFkCompanyBusinessUnit($priceProductMerchantRelationshipStorageTransfer->getIdCompanyBusinessUnit())
            ->setPriceKey($priceProductMerchantRelationshipStorageTransfer->getPriceKey())
            ->setData($this->formatData($priceProductMerchantRelationshipStorageTransfer))
            ->setIsSendingToQueue($this->getFactory()->getConfig()->isSendingToQueue())
            ->save();
    }

    /**
     * @param string[] $priceKeys
     *
     * @return void
     */
    public function deletePriceProductConcretesByPriceKeys(
        array $priceKeys
    ): void {
        if (empty($priceKeys)) {
            return;
        }

        $priceProductAbstractMerchantRelationshipStorageEntities = $this->getFactory()
            ->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->filterByPriceKey_In($priceKeys)
            ->find();

        foreach ($priceProductAbstractMerchantRelationshipStorageEntities as $priceProductAbstractMerchantRelationshipStorageEntity) {
            $priceProductAbstractMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @param int[] $merchantRelationshipIds
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function deletePriceProductConcretesByMerchantRelationshipIdsAndProductConcreteIds(array $merchantRelationshipIds, array $productConcreteIds): void
    {
        $priceProductConcreteMerchantRelationshipStorageEntities = $this->getFactory()
            ->createPriceProductConcreteMerchantRelationshipStorageQuery()
            ->distinct()
            ->addJoin(
                SpyPriceProductConcreteMerchantRelationshipStorageTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
                Criteria::LEFT_JOIN
            )
            ->where(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP
                . ' IN (' . implode(',', $merchantRelationshipIds) . ')')
            ->filterByFkProduct_In($productConcreteIds)
            ->find();

        foreach ($priceProductConcreteMerchantRelationshipStorageEntities as $priceProductConcreteMerchantRelationshipStorageEntity) {
            $priceProductConcreteMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @param int[] $merchantRelationshipIds
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function deletePriceProductAbstractsByMerchantRelationshipIdsAndProductAbstractIds(array $merchantRelationshipIds, array $productAbstractIds): void
    {
        $priceProductAbstractMerchantRelationshipStorageEntities = $this->getFactory()
            ->createPriceProductAbstractMerchantRelationshipStorageQuery()
            ->distinct()
            ->addJoin(
                SpyPriceProductAbstractMerchantRelationshipStorageTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
                SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT,
                Criteria::LEFT_JOIN
            )
            ->where(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATIONSHIP
                . ' IN (' . implode(',', $merchantRelationshipIds) . ')')
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        foreach ($priceProductAbstractMerchantRelationshipStorageEntities as $priceProductAbstractMerchantRelationshipStorageEntity) {
            $priceProductAbstractMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return array
     */
    protected function formatData(PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer): array
    {
        return [
            'prices' => $priceProductMerchantRelationshipStorageTransfer->getPrices(),
        ];
    }
}
