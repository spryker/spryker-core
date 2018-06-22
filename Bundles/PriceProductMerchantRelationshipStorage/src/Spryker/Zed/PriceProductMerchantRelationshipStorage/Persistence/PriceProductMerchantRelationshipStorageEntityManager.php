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
     * @param array $priceProductMerchantRelationshipStorageEntityMap
     * @param string $priceKey
     * @param string $priceProductStorageEntityClass
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface|\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage|\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage
     */
    protected function getPriceProductMerchantRelationshipStorageEntity(
        array $priceProductMerchantRelationshipStorageEntityMap,
        string $priceKey,
        string $priceProductStorageEntityClass
    ): ActiveRecordInterface {

        if (isset($priceProductMerchantRelationshipStorageEntityMap[$priceKey])) {
            return $priceProductMerchantRelationshipStorageEntityMap[$priceKey];
        }

        return new $priceProductStorageEntityClass();
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $priceProductMerchantRelationshipStorageEntity
     * @param int $idProduct
     *
     * @return void
     */
    protected function setProductForeignKey(
        ActiveRecordInterface $priceProductMerchantRelationshipStorageEntity,
        int $idProduct
    ): void {

        if ($priceProductMerchantRelationshipStorageEntity instanceof SpyPriceProductConcreteMerchantRelationshipStorage) {
            $priceProductMerchantRelationshipStorageEntity->setFkProduct($idProduct);
            return;
        }

        if ($priceProductMerchantRelationshipStorageEntity instanceof SpyPriceProductAbstractMerchantRelationshipStorage) {
            $priceProductMerchantRelationshipStorageEntity->setFkProductAbstract($idProduct);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransferCollection
     * @param array $existingPriceProductMerchantRelationshipStorageEntityMap
     * @param string $priceProductStorageEntityClass
     *
     * @return void
     */
    protected function writePriceProductMerchantRelationshipStorage(
        array $priceProductMerchantRelationshipStorageTransferCollection,
        array $existingPriceProductMerchantRelationshipStorageEntityMap,
        string $priceProductStorageEntityClass
    ): void {
        foreach ($priceProductMerchantRelationshipStorageTransferCollection as $priceProductMerchantRelationshipStorageTransfer) {
            $priceKey = $this->buildPriceKey($priceProductMerchantRelationshipStorageTransfer);

            $priceProductMerchantRelationshipStorageEntity = $this->getPriceProductMerchantRelationshipStorageEntity(
                $existingPriceProductMerchantRelationshipStorageEntityMap,
                $priceKey,
                $priceProductStorageEntityClass
            );

            if (!$priceProductMerchantRelationshipStorageTransfer->getPrices()) {
                if (!$priceProductMerchantRelationshipStorageEntity->isNew()) {
                    $priceProductMerchantRelationshipStorageEntity->delete();
                }
                continue;
            }

            $data = [
                'prices' => $priceProductMerchantRelationshipStorageTransfer->getPrices(),
            ];

            $priceProductMerchantRelationshipStorageEntity
                ->setPriceKey($priceKey)
                ->setFkMerchantRelationship($priceProductMerchantRelationshipStorageTransfer->getIdMerchantRelationship())
                ->setData($data)
                ->setIsSendingToQueue(true);

            $this->setProductForeignKey($priceProductMerchantRelationshipStorageEntity, $priceProductMerchantRelationshipStorageTransfer->getIdProduct());

            $priceProductMerchantRelationshipStorageEntity->save();
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
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return string
     */
    protected function buildPriceKey(PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer): string
    {
        return implode(':', [
            $priceProductMerchantRelationshipStorageTransfer->getStoreName(),
            $priceProductMerchantRelationshipStorageTransfer->getIdProduct(),
            $priceProductMerchantRelationshipStorageTransfer->getIdMerchantRelationship(),
        ]);
    }
}
