<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Plugin\ProductPageSearch\DataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
 */
class ProductListDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    protected const COL_CONCRETE_PRODUCT_COUNT = 'concrete_product_count';
    protected const COL_ID_PRODUCT_ABSTRACT = 'col_id_product_abstract';
    protected const COL_TYPE = 'col_type';
    protected const COL_ID_PRODUCT_LIST = 'col_id_product_list';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $loadTransfer)
    {
        $productList = $this->filterProductListIds(
            $this->getProductList($loadTransfer->getProductAbstractIds()),
            $this->getProductConcreteCountByProductAbstractIds($loadTransfer->getProductAbstractIds())
        );

        $categoryProductList = $this->getCategoryProductList($loadTransfer->getProductAbstractIds());

        $totalProductList = array_merge($productList, $categoryProductList);

        $updatedPayloadTransfers = $this->updatePayloadTransfers(
            $loadTransfer->getPayloadTransfers(),
            $this->mapProductListIds($totalProductList)
        );

        $loadTransfer->setPayloadTransfers($updatedPayloadTransfers);

        return $loadTransfer;
    }

    /**
     * @param array $totalProductListIds
     *
     * @return array
     */
    protected function mapProductListIds(array $totalProductListIds): array
    {
        $mappedProductListIds = [];
        foreach ($totalProductListIds as $productList) {
            $idProductAbstract = $productList[static::COL_ID_PRODUCT_ABSTRACT];
            $type = $productList[static::COL_TYPE];
            $idProductList = $productList[static::COL_ID_PRODUCT_LIST];

            $mappedProductListIds[$idProductAbstract][$type][] = $idProductList;
        }

        return $mappedProductListIds;
    }

    /**
     * @param array $productListIds
     * @param array $productConcreteCountByProductAbstractIds
     *
     * @return array
     */
    protected function filterProductListIds(array $productListIds, $productConcreteCountByProductAbstractIds): array
    {
        return array_filter($productListIds, function (array $item) use ($productConcreteCountByProductAbstractIds) {
            $idProductAbstract = $item[static::COL_ID_PRODUCT_ABSTRACT];

            return $this->isAllConcreteProductsInList($item, $productConcreteCountByProductAbstractIds[$idProductAbstract][static::COL_CONCRETE_PRODUCT_COUNT]);
        });
    }

    /**
     * @param array $item
     * @param int $totalProductConcreteCount
     *
     * @return bool
     */
    protected function isAllConcreteProductsInList(array $item, int $totalProductConcreteCount): bool
    {
        return $item[static::COL_CONCRETE_PRODUCT_COUNT] === $totalProductConcreteCount;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getProductConcreteCountByProductAbstractIds(array $productAbstractIds): array
    {
        return SpyProductQuery::create()
            ->addAsColumn(static::COL_CONCRETE_PRODUCT_COUNT, sprintf('COUNT(%s)', SpyProductTableMap::COL_ID_PRODUCT))
            ->addAsColumn(static::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->select([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
            ])
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->groupBy([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
            ])
            ->find()
            ->toArray(static::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getProductList(array $productAbstractIds): array
    {
        return SpyProductListProductConcreteQuery::create()
            ->addAsColumn(static::COL_CONCRETE_PRODUCT_COUNT, sprintf('COUNT(%s)', SpyProductListProductConcreteTableMap::COL_FK_PRODUCT))
            ->addAsColumn(static::COL_ID_PRODUCT_LIST, SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST)
            ->addAsColumn(static::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(static::COL_TYPE, SpyProductListTableMap::COL_TYPE)
            ->select([
                SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST,
            ])
            ->innerJoinWithSpyProduct()
            ->useSpyProductQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse()
            ->innerJoinWithSpyProductList()
            ->groupBy([
                SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST,
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductListTableMap::COL_TYPE,
            ])
            ->find()
            ->toArray();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getCategoryProductList(array $productAbstractIds): array
    {
        return SpyProductListCategoryQuery::create()
            ->select([
                SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST,
            ])
            ->withColumn(SpyProductListTableMap::COL_TYPE, static::COL_TYPE)
            ->withColumn(SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST, static::COL_ID_PRODUCT_LIST)
            ->withColumn(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT, static::COL_ID_PRODUCT_ABSTRACT)
            ->innerJoinWithSpyCategory()
            ->useSpyCategoryQuery()
            ->innerJoinWithSpyProductCategory()
            ->useSpyProductCategoryQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse()
            ->endUse()
            ->innerJoinWithSpyProductList()
            ->groupBy([
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST,
                SpyProductListTableMap::COL_TYPE,
            ])
            ->find()
            ->toArray();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductPageType()
    {
        return 'product-list';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     * @param array $mappedProductListIds
     *
     * @return array
     */
    protected function updatePayloadTransfers(array $payloadTransfers, array $mappedProductListIds): array
    {
        foreach ($payloadTransfers as $payloadTransfer) {
            $lists = $mappedProductListIds[$payloadTransfer->getIdProductAbstract()] ?? null;

            $payloadTransfer->setLists($lists);
        }

        return $payloadTransfers;
    }
}
