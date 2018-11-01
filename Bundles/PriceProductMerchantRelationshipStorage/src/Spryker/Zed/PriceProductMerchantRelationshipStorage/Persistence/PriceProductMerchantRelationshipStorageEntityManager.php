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
use Propel\Runtime\Exception\PropelException;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

class PriceProductMerchantRelationshipStorageEntityManager extends AbstractEntityManager implements PriceProductMerchantRelationshipStorageEntityManagerInterface
{
    protected const PRICE_KEY_SEPARATOR = ':';

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
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage|\Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage
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
     * @param array $priceProductMerchantRelationshipStorageTransfers
     * @param array $existingPriceProductMerchantRelationshipStorageEntityMap
     * @param string $priceProductStorageEntityClass
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function writePriceProductMerchantRelationshipStorage(
        array $priceProductMerchantRelationshipStorageTransfers,
        array $existingPriceProductMerchantRelationshipStorageEntityMap,
        string $priceProductStorageEntityClass
    ): void {
        $groupedPrices = [];

        foreach ($priceProductMerchantRelationshipStorageTransfers as $storageTransfer) {
            $groupedPrices[$storageTransfer->getIdProduct()][$storageTransfer->getIdCompanyBusinessUnit()][] = $storageTransfer;
        }

        foreach ($groupedPrices as $idProduct => $pricesPerProduct) {
            /** @var \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $storageTransfers */
            foreach ($pricesPerProduct as $idCompanyBusinessUnit => $storageTransfers) {
                $prices = [];
                foreach ($storageTransfers as $storageTransfer) {
                    $prices[$storageTransfer->getIdMerchantRelationship()] = $storageTransfer->getPrices();
                }

                $priceKey = $this->buildPriceKey($storageTransfers[0]);
                $priceProductMerchantRelationshipStorageEntity = $this->getPriceProductMerchantRelationshipStorageEntity(
                    $existingPriceProductMerchantRelationshipStorageEntityMap,
                    $priceKey,
                    $priceProductStorageEntityClass
                );

                try {
                    $this->applyChangesToEntity($priceProductMerchantRelationshipStorageEntity, $priceKey, $idCompanyBusinessUnit, $prices, $idProduct);
                    $priceProductMerchantRelationshipStorageEntity->save();
                } catch (PropelException $exception) {
                    ErrorLogger::getInstance()->log($exception);
                    if ($priceProductMerchantRelationshipStorageEntity instanceof SpyPriceProductAbstractMerchantRelationshipStorage) {
                        $priceProductMerchantRelationshipStorageEntity = SpyPriceProductAbstractMerchantRelationshipStorageQuery::create()
                            ->filterByPriceKey($priceKey)
                            ->findOne();
                    } elseif ($priceProductMerchantRelationshipStorageEntity instanceof SpyPriceProductConcreteMerchantRelationshipStorage) {
                        $priceProductMerchantRelationshipStorageEntity = SpyPriceProductConcreteMerchantRelationshipStorageQuery::create()
                            ->filterByPriceKey($priceKey)
                            ->findOne();
                    }

                    if ($priceProductMerchantRelationshipStorageEntity === null) {
                        throw $exception;
                    }
                    $this->applyChangesToEntity($priceProductMerchantRelationshipStorageEntity, $priceKey, $idCompanyBusinessUnit, $prices, $idProduct);
                    $priceProductMerchantRelationshipStorageEntity->save();
                }
            }
        }
    }

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage $priceProductMerchantRelationshipStorageEntity
     * @param string $priceKey
     * @param int $idCompanyBusinessUnit
     * @param array $prices
     * @param int $idProduct
     *
     * @return void
     */
    protected function applyChangesToEntity($priceProductMerchantRelationshipStorageEntity, $priceKey, $idCompanyBusinessUnit, array $prices, $idProduct): void
    {
        $data = [
            'prices' => $prices,
        ];

        $priceProductMerchantRelationshipStorageEntity
            ->setPriceKey($priceKey)
            ->setFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->setData($data)
            ->setIsSendingToQueue(true);

        $this->setProductForeignKey($priceProductMerchantRelationshipStorageEntity, $idProduct);
    }

    /**
     * @param int $idCompanyBusinessUnit
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deletePriceProductAbstractByCompanyBusinessUnitAndIdProductAbstract(
        int $idCompanyBusinessUnit,
        int $idProductAbstract
    ): void {
        $priceProductAbstractMerchantRelationshipStorageEntities = SpyPriceProductAbstractMerchantRelationshipStorageQuery::create()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->filterByFkProductAbstract($idProductAbstract)
            ->find();

        foreach ($priceProductAbstractMerchantRelationshipStorageEntities as $priceProductAbstractMerchantRelationshipStorageEntity) {
            $priceProductAbstractMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @param int $idCompanyBusinessUnit
     * @param int $idProduct
     *
     * @return void
     */
    public function deletePriceProductConcreteByCompanyBusinessUnitAndIdProduct(
        int $idCompanyBusinessUnit,
        int $idProduct
    ): void {
        $priceProductConcreteMerchantRelationshipStorageEntities = SpyPriceProductConcreteMerchantRelationshipStorageQuery::create()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->filterByFkProduct($idProduct)
            ->find();

        foreach ($priceProductConcreteMerchantRelationshipStorageEntities as $priceProductConcreteMerchantRelationshipStorageEntity) {
            $priceProductConcreteMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function deletePriceProductAbstractByCompanyBusinessUnit(
        int $idCompanyBusinessUnit
    ): void {
        $priceProductAbstractMerchantRelationshipStorageEntities = SpyPriceProductAbstractMerchantRelationshipStorageQuery::create()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->find();

        foreach ($priceProductAbstractMerchantRelationshipStorageEntities as $priceProductAbstractMerchantRelationshipStorageEntity) {
            $priceProductAbstractMerchantRelationshipStorageEntity->delete();
        }
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function deletePriceProductConcreteByCompanyBusinessUnit(
        int $idCompanyBusinessUnit
    ): void {
        $priceProductConcreteMerchantRelationshipStorageEntities = SpyPriceProductConcreteMerchantRelationshipStorageQuery::create()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
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
        return implode(static::PRICE_KEY_SEPARATOR, [
            $priceProductMerchantRelationshipStorageTransfer->getStoreName(),
            $priceProductMerchantRelationshipStorageTransfer->getIdProduct(),
            $priceProductMerchantRelationshipStorageTransfer->getIdCompanyBusinessUnit(),
        ]);
    }
}
