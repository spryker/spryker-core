<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet\PriceProductMerchantRelationshipDataSetInterface;

class PriceProductMerchantRelationshipWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @see \Spryker\Zed\PriceProductMerchantRelationship\Dependency\PriceProductMerchantRelationshipEvents;
     */
    protected const ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE = 'Entity.spy_price_product_store.create';
    protected const ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE = 'Entity.spy_price_product_store.update';
    protected const ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_CREATE = 'Entity.spy_price_product_merchant_relationship.create';
    protected const ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_UPDATE = 'Entity.spy_price_product_merchant_relationship.update';
    protected const ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_DELETE = 'Entity.spy_price_product_merchant_relationship.delete';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceProductStoreEntity = $this->getPriceProductStoreEntityForMerchantRelationship($dataSet);

        if ($priceProductStoreEntity->getPriceProductMerchantRelationships()->count() !== 0) {
            return;
        }

        $this->savePriceProductMerchantRelationship($dataSet, $priceProductStoreEntity->getPrimaryKey());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    protected function getPriceProductStoreEntityForMerchantRelationship(
        DataSetInterface $dataSet
    ): SpyPriceProductStore {
        $priceProductStoreEntity = $this->findExistingPriceProductStoreEntity($dataSet);

        if (!$priceProductStoreEntity) {
            return $this->getNewPriceProductStoreEntity($dataSet);
        }

        $netPrice = (int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_NET];
        $grossPrice = (int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_GROSS];
        if ($priceProductStoreEntity->getGrossPrice() === $grossPrice && $priceProductStoreEntity->getNetPrice() === $netPrice) {
            $this->addPublishEvents(
                static::ENTITY_SPY_PRICE_PRODUCT_STORE_UPDATE,
                $priceProductStoreEntity->getPrimaryKey()
            );

            return $priceProductStoreEntity;
        }

        //todo check if it correctly trigger event in listener
        $this->removeNotActualRelation(
            $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_MERCHANT_RELATIONSHIP],
            $priceProductStoreEntity->getIdPriceProductStore()
        );

        return $this->getNewPriceProductStoreEntity($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $idPriceProductStore
     *
     * @return void
     */
    protected function savePriceProductMerchantRelationship(DataSetInterface $dataSet, string $idPriceProductStore): void
    {
        $priceProductMerchantRelationshipQuery = SpyPriceProductMerchantRelationshipQuery::create()
            ->filterByFkMerchantRelationship($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_MERCHANT_RELATIONSHIP])
            ->filterByFkPriceProductStore($idPriceProductStore);

        if (!empty($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_ABSTRACT])) {
            $priceProductMerchantRelationshipQuery->filterByFkProductAbstract($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_ABSTRACT]);
        } else {
            $priceProductMerchantRelationshipQuery->filterByFkProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_CONCRETE]);
        }

        $priceProductMerchantRelationshipEntity = $priceProductMerchantRelationshipQuery->findOneOrCreate();

        $priceProductMerchantRelationshipEntity->save();
    }

    /**
     * @param int $idMerchantRelationship
     * @param string $idPriceProductStoreEntity
     *
     * @return void
     */
    protected function removeNotActualRelation(int $idMerchantRelationship, string $idPriceProductStoreEntity): void
    {
        $priceProductMerchantRelationshipEntities = SpyPriceProductMerchantRelationshipQuery::create()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->filterByFkPriceProductStore($idPriceProductStoreEntity)
            ->find();

        foreach ($priceProductMerchantRelationshipEntities as $priceProductMerchantRelationshipEntity) {
            $this->addPublishEvents(
                static::ENTITY_SPY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_DELETE,
                $priceProductMerchantRelationshipEntity->getPrimaryKey()
            );

            $priceProductMerchantRelationshipEntity->delete();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore|null
     */
    protected function findExistingPriceProductStoreEntity(DataSetInterface $dataSet): ?SpyPriceProductStore
    {
        return SpyPriceProductStoreQuery::create()
            ->usePriceProductMerchantRelationshipQuery()
                ->filterByFkMerchantRelationship($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_MERCHANT_RELATIONSHIP])
            ->endUse()
            ->filterByFkStore($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_STORE])
            ->filterByFkCurrency($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_CURRENCY])
            ->filterByFkPriceProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT])
            ->findOne();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    protected function getNewPriceProductStoreEntity(DataSetInterface $dataSet): SpyPriceProductStore
    {
        $netPrice = (int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_NET];
        $grossPrice = (int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_GROSS];

        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_STORE])
            ->filterByFkCurrency($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_CURRENCY])
            ->filterByFkPriceProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT])
            ->filterByNetPrice($netPrice)
            ->filterByGrossPrice($grossPrice)
            ->findOneOrCreate();

        $isNewRecord = $priceProductStoreEntity->isNew();

        $priceProductStoreEntity->save();

        if ($isNewRecord) {
            $this->addPublishEvents(
                static::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE,
                $priceProductStoreEntity->getPrimaryKey()
            );
        }

        return $priceProductStoreEntity;
    }
}
