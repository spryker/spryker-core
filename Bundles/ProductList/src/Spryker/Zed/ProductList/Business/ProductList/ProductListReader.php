<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductList;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReaderInterface;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationReaderInterface;
use Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface;
use Spryker\Zed\ProductList\Persistence\ProductListRepository;
use Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface;

class ProductListReader implements ProductListReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface
     */
    protected $productListRepository;

    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReaderInterface
     */
    protected $productListCategoryRelationReader;

    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationReaderInterface
     */
    private $productListProductConcreteRelationReader;

    /**
     * @var \Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface $productListRepository
     * @param \Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReaderInterface $productListCategoryRelationReader
     * @param \Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationReaderInterface $productListProductConcreteRelationReader
     * @param \Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductListRepositoryInterface $productListRepository,
        ProductListCategoryRelationReaderInterface $productListCategoryRelationReader,
        ProductListProductConcreteRelationReaderInterface $productListProductConcreteRelationReader,
        ProductListToProductFacadeInterface $productFacade
    ) {
        $this->productListRepository = $productListRepository;
        $this->productListCategoryRelationReader = $productListCategoryRelationReader;
        $this->productListProductConcreteRelationReader = $productListProductConcreteRelationReader;
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductBlacklistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->productListRepository->getProductBlacklistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function getProductListsByProductIds(array $productConcreteIds): array
    {
        $productConcreteIdsToProductAbstractIdsMap = $this->productFacade->getProductAbstractIdsByProductConcreteIds($productConcreteIds);

        $productConcreteLists = $this->mapProductListIdsByIdProductConcreteAndType(
            $this->productListRepository->getProductListIdsByProductIds($productConcreteIds)
        );

        $productAbstractLists = $this->getProductAbstractListIdsByProductAbstractIds(
            array_values($productConcreteIdsToProductAbstractIdsMap)
        );

        return $this->mergeProductConcreteAndProductAbstractLists($productConcreteLists, $productAbstractLists, $productConcreteIdsToProductAbstractIdsMap);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractListIdsByProductAbstractIds(array $productAbstractIds): array
    {
        $productBlacklistsByProductAbstractIds = $this->getProductBlacklistsByProductAbstractIds($productAbstractIds);
        $productWhitelistsByProductAbstractIds = $this->getProductWhitelistsByProductAbstractIds($productAbstractIds);
        $categoryProductList = $this->getCategoryProductList($productAbstractIds);

        $totalProductList = array_merge($productBlacklistsByProductAbstractIds, $productWhitelistsByProductAbstractIds, $categoryProductList);

        return $this->mapProductListIdsByIdProductAbstractAndType($totalProductList);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->productListRepository->getAbstractProductWhitelistIds($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getCategoryWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return $this->productListRepository->getCategoryWhitelistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductBlacklistIdsByIdProduct(int $idProduct): array
    {
        return array_unique(
            array_merge(
                $this->productListRepository->getProductConcreteProductListIdsForType(
                    $idProduct,
                    SpyProductListTableMap::COL_TYPE_BLACKLIST
                ),
                $this->productListRepository->getProductConcreteProductListIdsRelatedToCategoriesForType(
                    $idProduct,
                    SpyProductListTableMap::COL_TYPE_BLACKLIST
                )
            )
        );
    }

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProduct(int $idProduct): array
    {
        return array_unique(
            array_merge(
                $this->productListRepository->getProductConcreteProductListIdsForType(
                    $idProduct,
                    SpyProductListTableMap::COL_TYPE_WHITELIST
                ),
                $this->productListRepository->getProductConcreteProductListIdsRelatedToCategoriesForType(
                    $idProduct,
                    SpyProductListTableMap::COL_TYPE_WHITELIST
                )
            )
        );
    }

    /**
     * @param string[] $productConcreteSkus
     * @param int[] $blackListIds
     *
     * @return string[]
     */
    public function getProductConcreteSkusInBlacklists(array $productConcreteSkus, array $blackListIds): array
    {
        return $this->productListRepository->getProductConcreteSkusInBlacklists($productConcreteSkus, $blackListIds);
    }

    /**
     * @param string[] $productConcreteSkus
     * @param int[] $whiteListIds
     *
     * @return string[]
     */
    public function getProductConcreteSkusInWhitelists(array $productConcreteSkus, array $whiteListIds): array
    {
        return $this->productListRepository->getProductConcreteSkusInWhitelists($productConcreteSkus, $whiteListIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getProductListById(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        $productListTransfer->requireIdProductList();

        $productListTransfer = $this->productListRepository
            ->getProductListById($productListTransfer->getIdProductList());

        if (!$productListTransfer->getIdProductList()) {
            return $productListTransfer;
        }

        $productListCategoryRelationTransfer = new ProductListCategoryRelationTransfer();
        $productListCategoryRelationTransfer->setIdProductList($productListTransfer->getIdProductList());
        $productListCategoryRelationTransfer = $this->productListCategoryRelationReader
            ->getProductListCategoryRelation($productListCategoryRelationTransfer);
        $productListTransfer->setProductListCategoryRelation($productListCategoryRelationTransfer);

        $productListProductConcreteRelationTransfer = new ProductListProductConcreteRelationTransfer();
        $productListProductConcreteRelationTransfer->setIdProductList($productListTransfer->getIdProductList());
        $productListProductConcreteRelationTransfer = $this->productListProductConcreteRelationReader
            ->getProductListProductConcreteRelation($productListProductConcreteRelationTransfer);
        $productListTransfer->setProductListProductConcreteRelation($productListProductConcreteRelationTransfer);

        return $productListTransfer;
    }

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array
    {
        return array_unique(
            array_merge(
                $this->productListRepository->getProductAbstractIdsRelatedToProductConcrete($productListIds),
                $this->productListRepository->getProductAbstractIdsRelatedToCategories($productListIds)
            )
        );
    }

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductListIds(array $productListIds): array
    {
        return array_unique(
            array_merge(
                $this->productListRepository->getProductConcreteIdsRelatedToProductLists($productListIds),
                $this->productListRepository->getProductConcreteIdsRelatedToProductListsCategories($productListIds)
            )
        );
    }

    /**
     * @param array $productConcreteLists
     * @param array $productAbstractLists
     * @param array $concreteToAbstractMap
     *
     * @return array
     */
    protected function mergeProductConcreteAndProductAbstractLists(array $productConcreteLists, array $productAbstractLists, array $concreteToAbstractMap): array
    {
        $mergedProductConcreteAndProductAbstractLists = [];

        foreach ($concreteToAbstractMap as $idProductConcrete => $idProductAbstract) {
            $productAbstractList = $productAbstractLists[$idProductAbstract] ?? [];
            $productConcreteList = $productConcreteLists[$idProductConcrete] ?? [];

            $mergedList = $productAbstractList + $productConcreteList;

            if (count($mergedList)) {
                $mergedProductConcreteAndProductAbstractLists[$idProductConcrete] = $mergedList;
            }
        }

        return $mergedProductConcreteAndProductAbstractLists;
    }

    /**
     * @param array $productAbstractListsIds
     *
     * @return array
     */
    protected function mapProductListIdsByIdProductAbstractAndType(array $productAbstractListsIds): array
    {
        $mappedProductListIds = [];
        foreach ($productAbstractListsIds as $productList) {
            $idProductAbstract = $productList[ProductListRepository::COL_ID_PRODUCT_ABSTRACT];
            $type = $productList[ProductListRepository::COL_TYPE];
            $idProductList = $productList[ProductListRepository::COL_ID_PRODUCT_LIST];

            $mappedProductListIds[$idProductAbstract][$type][] = $idProductList;
        }

        return $mappedProductListIds;
    }

    /**
     * @param array $productConcreteListsIds
     *
     * @return array
     */
    protected function mapProductListIdsByIdProductConcreteAndType(array $productConcreteListsIds): array
    {
        $mappedProductListIds = [];
        foreach ($productConcreteListsIds as $productList) {
            $idProduct = $productList[SpyProductListProductConcreteTableMap::COL_FK_PRODUCT];
            $type = $productList[ProductListRepository::COL_TYPE];
            $idProductList = $productList[ProductListRepository::COL_ID_PRODUCT_LIST];

            $mappedProductListIds[$idProduct][$type][] = $idProductList;
        }

        return $mappedProductListIds;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function getCategoryProductList(array $productAbstractIds): array
    {
        return $this->productListRepository->getProductListByProductAbstractIdsThroughCategory($productAbstractIds);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function getProductBlacklistsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->productListRepository->getProductBlacklistsByProductAbstractIds($productAbstractIds);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function getProductWhitelistsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->productListRepository->getProductWhitelistsByProductAbstractIds($productAbstractIds);
    }
}
