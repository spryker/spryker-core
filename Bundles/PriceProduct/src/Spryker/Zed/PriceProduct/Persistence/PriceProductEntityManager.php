<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefault;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductPersistenceFactory getFactory()
 */
class PriceProductEntityManager extends AbstractEntityManager implements PriceProductEntityManagerInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter::deleteOrphanPriceProductStoreEntities()} instead.
     *
     * @return void
     */
    public function deleteOrphanPriceProductStoreEntities(): void
    {
        $priceProductStoreQuery = $this->getFactory()
            ->createPriceProductStoreQuery();

        $this->getFactory()
            ->createPriceProductDimensionQueryExpander()
            ->expandPriceProductStoreQueryWithPriceDimensionForDelete(
                $priceProductStoreQuery,
                new PriceProductCriteriaTransfer(),
            );

        if (!$priceProductStoreQuery->getAsColumns()) {
            return;
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $priceProductStoreCollection */
        $priceProductStoreCollection = $priceProductStoreQuery->find();
        $priceProductStoreCollection->delete();
    }

    /**
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function deletePriceProductStore(int $idPriceProductStore): void
    {
        $priceProductStoreEntity = $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByIdPriceProductStore($idPriceProductStore)
            ->findOne();

        if (!$priceProductStoreEntity) {
            return;
        }

        $priceProductStoreEntity->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer $spyPriceProductDefaultEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer
     */
    public function savePriceProductDefaultEntity(
        SpyPriceProductDefaultEntityTransfer $spyPriceProductDefaultEntityTransfer
    ): SpyPriceProductDefaultEntityTransfer {
        $priceProductMapper = $this->getFactory()->createPriceProductMapper();
        $priceProductDefaultEntity = $priceProductMapper->mapPriceProductDefaultTransferToPriceProductEntity(
            $spyPriceProductDefaultEntityTransfer,
            new SpyPriceProductDefault(),
        );
        $priceProductDefaultEntity->save();

        return $priceProductMapper->mapPriceProductDefaultEntityToPriceProductDefaultTransfer(
            $priceProductDefaultEntity,
            $spyPriceProductDefaultEntityTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function deletePriceProductStoreByPriceProductTransfer(PriceProductTransfer $priceProductTransfer): void
    {
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer */
        $currencyTransfer = $moneyValueTransfer->requireCurrency()->getCurrency();

        $moneyValueTransfer
            ->requireCurrency();

        /** @var \Propel\Runtime\Collection\ObjectCollection $priceProductStoreCollection */
        $priceProductStoreCollection = $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByFkCurrency($currencyTransfer->getIdCurrency())
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->filterByFkStore($moneyValueTransfer->getFkStore())
            ->find();
        $priceProductStoreCollection->delete();
    }

    /**
     * @param int $idPriceProduct
     *
     * @return void
     */
    public function deletePriceProductById(int $idPriceProduct): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $priceProductCollection */
        $priceProductCollection = $this->getFactory()
            ->createPriceProductQuery()
            ->filterByIdPriceProduct($idPriceProduct)
            ->find();
        $priceProductCollection->delete();
    }

    /**
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function deletePriceProductDefaultsByPriceProductStoreId(int $idPriceProductStore): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $priceProductDefaultCollection */
        $priceProductDefaultCollection = $this->getFactory()
            ->createPriceProductDefaultQuery()
            ->filterByFkPriceProductStore($idPriceProductStore)
            ->find();
        $priceProductDefaultCollection->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deletePriceProductDefaults(
        PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
    ): void {
        if (!$priceProductCollectionDeleteCriteriaTransfer->getPriceProductDefaultIds()) {
            return;
        }
        $priceProductDefaultQuery = $this->getFactory()
            ->createPriceProductDefaultQuery()
            ->filterByIdPriceProductDefault_In(
                $priceProductCollectionDeleteCriteriaTransfer->getPriceProductDefaultIds(),
            );

        if ($priceProductCollectionDeleteCriteriaTransfer->getPriceProductStoreIds()) {
            $priceProductDefaultQuery->filterByFkPriceProductStore_In(
                $priceProductCollectionDeleteCriteriaTransfer->getPriceProductStoreIds(),
            );
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $priceProductDefaultCollection */
        $priceProductDefaultCollection = $priceProductDefaultQuery->find();

        $priceProductDefaultCollection->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int
     */
    public function savePriceProductForProductConcrete(PriceProductTransfer $priceProductTransfer): int
    {
        $priceProductTransfer
            ->requireFkPriceType()
            ->requireIdProduct();

        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $priceProductEntity */
        $priceProductEntity = $this->getFactory()
            ->createPriceProductQuery()
            ->filterByFKProduct($priceProductTransfer->getIdProduct())
            ->filterByFkPriceType($priceProductTransfer->getFkPriceType())
            ->findOneOrCreate();

        $priceProductEntity->save();

        return $priceProductEntity->getIdPriceProduct();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int
     */
    public function savePriceProductForProductAbstract(PriceProductTransfer $priceProductTransfer): int
    {
        $priceProductTransfer
            ->requireFkPriceType()
            ->requireIdProductAbstract();

        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $priceProductEntity */
        $priceProductEntity = $this->getFactory()
            ->createPriceProductQuery()
            ->filterByFkProductAbstract($priceProductTransfer->getIdProductAbstract())
            ->filterByFkPriceType($priceProductTransfer->getFkPriceType())
            ->findOneOrCreate();

        $priceProductEntity->save();

        return $priceProductEntity->getIdPriceProduct();
    }
}
