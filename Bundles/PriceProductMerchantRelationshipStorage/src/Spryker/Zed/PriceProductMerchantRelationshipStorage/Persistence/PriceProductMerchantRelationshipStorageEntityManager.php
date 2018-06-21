<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorageQuery;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorageQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

class PriceProductMerchantRelationshipStorageEntityManager extends AbstractEntityManager implements PriceProductMerchantRelationshipStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransferCollection
     * @param array $existingPriceProductMerchantRelationshipStorageEntityMap
     *
     * @return void
     */
    public function writePriceProductConcrete(
        array $priceProductMerchantRelationshipStorageTransferCollection,
        array $existingPriceProductMerchantRelationshipStorageEntityMap
    ): void {

        $priceProductStorageEntityClass = SpyPriceProductConcreteMerchantRelationshipStorage::class;
        $this->writePriceProductMerchantRelationshipStorage(
            $priceProductMerchantRelationshipStorageTransferCollection,
            $existingPriceProductMerchantRelationshipStorageEntityMap,
            $priceProductStorageEntityClass
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransferCollection
     * @param array $existingPriceProductMerchantRelationshipStorageEntityMap
     *
     * @return void
     */
    public function writePriceProductAbstract(
        array $priceProductMerchantRelationshipStorageTransferCollection,
        array $existingPriceProductMerchantRelationshipStorageEntityMap
    ): void {
        $priceProductStorageEntityClass = SpyPriceProductAbstractMerchantRelationshipStorage::class;
        $this->writePriceProductMerchantRelationshipStorage(
            $priceProductMerchantRelationshipStorageTransferCollection,
            $existingPriceProductMerchantRelationshipStorageEntityMap,
            $priceProductStorageEntityClass
        );
    }

    /**
     * @param array $PriceProductMerchantRelationshipStorageEntityMap
     * @param string $priceKey
     * @param string $priceProductStorageEntityClass
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface|\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage|\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage
     */
    protected function getPriceProductMerchantRelationshipStorageEntity(
        array $PriceProductMerchantRelationshipStorageEntityMap,
        string $priceKey,
        string $priceProductStorageEntityClass
    ): ActiveRecordInterface {

        if (isset($PriceProductMerchantRelationshipStorageEntityMap[$priceKey])) {
            return $PriceProductMerchantRelationshipStorageEntityMap[$priceKey];
        }

        return new $priceProductStorageEntityClass();
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $PriceProductMerchantRelationshipStorageEntity
     * @param int $idProduct
     *
     * @return void
     */
    protected function setProductForeignKey(
        ActiveRecordInterface $PriceProductMerchantRelationshipStorageEntity,
        int $idProduct
    ): void {

        if ($PriceProductMerchantRelationshipStorageEntity instanceof SpyPriceProductConcreteMerchantRelationshipStorage) {
            $PriceProductMerchantRelationshipStorageEntity->setFkProduct($idProduct);
            return;
        }

        if ($PriceProductMerchantRelationshipStorageEntity instanceof SpyPriceProductAbstractMerchantRelationshipStorage) {
            $PriceProductMerchantRelationshipStorageEntity->setFkProductAbstract($idProduct);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $PriceProductMerchantRelationshipStorageTransferCollection
     * @param array $existingPriceProductMerchantRelationshipStorageEntityMap
     * @param string $priceProductStorageEntityClass
     *
     * @return void
     */
    protected function writePriceProductMerchantRelationshipStorage(
        array $PriceProductMerchantRelationshipStorageTransferCollection,
        array $existingPriceProductMerchantRelationshipStorageEntityMap,
        string $priceProductStorageEntityClass
    ): void {
        foreach ($PriceProductMerchantRelationshipStorageTransferCollection as $PriceProductMerchantRelationshipStorageTransfer) {
            $priceKey = $this->buildPriceKey($PriceProductMerchantRelationshipStorageTransfer);

            $PriceProductMerchantRelationshipStorageEntity = $this->getPriceProductMerchantRelationshipStorageEntity(
                $existingPriceProductMerchantRelationshipStorageEntityMap,
                $priceKey,
                $priceProductStorageEntityClass
            );

            if (!$PriceProductMerchantRelationshipStorageTransfer->getPrices()) {
                if (!$PriceProductMerchantRelationshipStorageEntity->isNew()) {
                    $PriceProductMerchantRelationshipStorageEntity->delete();
                }
                continue;
            }

            $PriceProductMerchantRelationshipStorageEntity
                ->setPriceKey($priceKey)
                ->setFkMerchantRelationship($PriceProductMerchantRelationshipStorageTransfer->getIdMerchantRelationship())
                ->setData($PriceProductMerchantRelationshipStorageTransfer->getPrices())
                ->setIsSendingToQueue(true);

            $this->setProductForeignKey($PriceProductMerchantRelationshipStorageEntity, $PriceProductMerchantRelationshipStorageTransfer->getIdProduct());

            $PriceProductMerchantRelationshipStorageEntity->save();
        }
    }

    /**
     * @api
     *
     * @param int $idMerchantRelationship
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deletePriceProductAbstractByMerchantRelationshipAndIdProductAbstract(
        int $idMerchantRelationship,
        int $idProductAbstract
    ): void {
        $priceProductAbstractMerchantRelationshipStorageEntities = SpyPriceProductAbstractMerchantRelationshipStorageQuery::create()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->filterByFkProductAbstract($idProductAbstract)
            ->find();

        foreach ($priceProductAbstractMerchantRelationshipStorageEntities as $priceProductAbstractMerchantRelationshipStorageEntity) {
            $priceProductAbstractMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @api
     *
     * @param int $idMerchantRelationship
     * @param int $idProduct
     *
     * @return void
     */
    public function deletePriceProductConcreteByMerchantRelationshipAndIdProduct(
        int $idMerchantRelationship,
        int $idProduct
    ): void {
        $priceProductConcreteMerchantRelationshipStorageEntities = SpyPriceProductConcreteMerchantRelationshipStorageQuery::create()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->filterByFkProduct($idProduct)
            ->find();

        foreach ($priceProductConcreteMerchantRelationshipStorageEntities as $priceProductConcreteMerchantRelationshipStorageEntity) {
            $priceProductConcreteMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $PriceProductMerchantRelationshipStorageTransfer
     *
     * @return string
     */
    protected function buildPriceKey(PriceProductMerchantRelationshipStorageTransfer $PriceProductMerchantRelationshipStorageTransfer): string
    {
        return implode(':', [
            $PriceProductMerchantRelationshipStorageTransfer->getStoreName(),
            $PriceProductMerchantRelationshipStorageTransfer->getIdProduct(),
            $PriceProductMerchantRelationshipStorageTransfer->getIdMerchantRelationship(),
        ]);
    }
}
