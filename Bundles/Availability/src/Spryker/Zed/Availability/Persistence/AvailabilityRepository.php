<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Availability\Persistence\Exception\AvailabilityAbstractNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityPersistenceFactory getFactory()
 */
class AvailabilityRepository extends AbstractRepository implements AvailabilityRepositoryInterface
{
    protected const COL_ID_PRODUCT = 'id_product';

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityByIdProductConcreteAndStore(
        int $idProductConcrete,
        StoreTransfer $storeTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        $storeTransfer->requireIdStore();

        $availabilityEntity = $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->addJoin(SpyAvailabilityTableMap::COL_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->where(sprintf('%s = %d', SpyProductTableMap::COL_ID_PRODUCT, $idProductConcrete))
            ->findOne();

        if ($availabilityEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createAvailabilityMapper()
            ->mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
                $availabilityEntity,
                new ProductConcreteAvailabilityTransfer()
            );
    }

    /**
     * @param int[] $productConcreteIds
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[]
     */
    public function getMappedProductConcreteAvailabilitiesByProductConcreteIds(
        array $productConcreteIds,
        StoreTransfer $storeTransfer
    ): array {
        $storeTransfer->requireIdStore();

        $availabilityEntities = $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->addJoin(SpyAvailabilityTableMap::COL_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->where(sprintf('%s IN (%s)', SpyProductTableMap::COL_ID_PRODUCT, implode(',', $productConcreteIds)))
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, static::COL_ID_PRODUCT)
            ->find()
            ->toKeyIndex(static::COL_ID_PRODUCT);

        $productConcreteAvailabilityTransfers = [];
        $availabilityMapper = $this->getFactory()->createAvailabilityMapper();

        foreach ($availabilityEntities as $idProductConcrete => $availabilityEntity) {
            $productConcreteAvailabilityTransfer = $availabilityMapper->mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
                $availabilityEntity,
                new ProductConcreteAvailabilityTransfer()
            );

            $productConcreteAvailabilityTransfers[$idProductConcrete] = $productConcreteAvailabilityTransfer;
        }

        return $productConcreteAvailabilityTransfers;
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityBySkuAndStore(
        string $concreteSku,
        StoreTransfer $storeTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        $storeTransfer->requireIdStore();

        $availabilityEntity = $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterBySku($concreteSku)
            ->findOne();

        if ($availabilityEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createAvailabilityMapper()
            ->mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
                $availabilityEntity,
                new ProductConcreteAvailabilityTransfer()
            );
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findProductAbstractAvailabilityBySkuAndStore(
        string $abstractSku,
        StoreTransfer $storeTransfer
    ): ?ProductAbstractAvailabilityTransfer {
        $storeTransfer->requireIdStore();

        $availabilityAbstractEntityArray = $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByAbstractSku($abstractSku)
            ->useSpyAvailabilityQuery()
                ->filterByFkStore($storeTransfer->getIdStore())
            ->endUse()
            ->select([
                SpyAvailabilityAbstractTableMap::COL_ABSTRACT_SKU,
            ])->withColumn(SpyAvailabilityAbstractTableMap::COL_ABSTRACT_SKU, ProductAbstractAvailabilityTransfer::SKU)
            ->withColumn(SpyAvailabilityAbstractTableMap::COL_QUANTITY, ProductAbstractAvailabilityTransfer::AVAILABILITY)
            ->withColumn('GROUP_CONCAT(' . SpyAvailabilityTableMap::COL_IS_NEVER_OUT_OF_STOCK . ')', ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK)
            ->groupByAbstractSku()
            ->findOne();

        if ($availabilityAbstractEntityArray === null) {
            return null;
        }

        return $this->getFactory()
            ->createAvailabilityMapper()
            ->mapAvailabilityEntityToProductAbstractAvailabilityTransfer(
                $availabilityAbstractEntityArray,
                new ProductAbstractAvailabilityTransfer()
            );
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @throws \Spryker\Zed\Availability\Persistence\Exception\AvailabilityAbstractNotFoundException
     *
     * @return int
     */
    public function findIdProductAbstractAvailabilityBySku(
        string $abstractSku,
        StoreTransfer $storeTransfer
    ): int {
        $idAvailabilityAbstract = $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByAbstractSku($abstractSku)
            ->filterByFkStore($storeTransfer->getIdStore())
            ->select(SpyAvailabilityAbstractTableMap::COL_ID_AVAILABILITY_ABSTRACT)
            ->findOne();

        if ($idAvailabilityAbstract === null) {
            throw new AvailabilityAbstractNotFoundException(
                'You cannot update concrete availability without updating abstract availability first'
            );
        }

        return $idAvailabilityAbstract;
    }

    /**
     * @param string $concreteSku
     *
     * @return string|null
     */
    public function getAbstractSkuFromProductConcrete(string $concreteSku): ?string
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->useSpyProductQuery()
                ->filterBySku($concreteSku)
            ->endUse()
            ->select(SpyProductAbstractTableMap::COL_SKU)
            ->findOne();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string|null
     */
    public function getProductConcreteSkuByConcreteId(int $idProductConcrete): ?string
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->useSpyProductQuery()
                ->filterByIdProduct($idProductConcrete)
            ->endUse()
            ->select(SpyProductTableMap::COL_SKU)
            ->findOne();
    }

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWhereProductAvailabilityIsDefined(string $concreteSku): array
    {
        $availabilityEntities = $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->joinWithStore(Criteria::LEFT_JOIN)
            ->filterBySku($concreteSku)
            ->find();

        $storeEntities = [];
        foreach ($availabilityEntities as $availabilityEntity) {
            $storeEntities[] = $availabilityEntity->getStore();
        }

        return $this->getFactory()
            ->createStoreMapper()
            ->mapStoreEntitiesToStoreTransfers($storeEntities);
    }

    /**
     * @param string $productAbstractSku
     *
     * @return string[]
     */
    public function getProductConcreteSkusByAbstractProductSku(string $productAbstractSku): array
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->filterBySku($productAbstractSku)
            ->joinWithSpyProduct(Criteria::LEFT_JOIN)
            ->select(SpyProductTableMap::COL_SKU)
            ->find()
            ->getData();
    }
}
