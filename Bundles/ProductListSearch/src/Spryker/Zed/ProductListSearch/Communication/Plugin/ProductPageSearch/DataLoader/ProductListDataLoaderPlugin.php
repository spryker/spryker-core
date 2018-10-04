<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Plugin\ProductPageSearch\DataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepository;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface getRepository()
 */
class ProductListDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
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
            $idProductAbstract = $productList[ProductListSearchRepository::COL_ID_PRODUCT_ABSTRACT];
            $type = $productList[ProductListSearchRepository::COL_TYPE];
            $idProductList = $productList[ProductListSearchRepository::COL_ID_PRODUCT_LIST];

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
            if ($item[ProductListSearchRepository::COL_TYPE] !== $this->getRepository()->getValueForBlacklistType()) {
                return true;
            }

            $idProductAbstract = $item[ProductListSearchRepository::COL_ID_PRODUCT_ABSTRACT];

            return $this->isAllConcreteProductsInList(
                $item,
                $productConcreteCountByProductAbstractIds[$idProductAbstract][ProductListSearchRepository::COL_CONCRETE_PRODUCT_COUNT]
            );
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
        return $item[ProductListSearchRepository::COL_CONCRETE_PRODUCT_COUNT] === $totalProductConcreteCount;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getProductConcreteCountByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getRepository()
            ->getProductConcreteCountByProductAbstractIds($productAbstractIds);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getProductList(array $productAbstractIds): array
    {
        return $this->getRepository()
            ->getProductList($productAbstractIds);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getCategoryProductList(array $productAbstractIds): array
    {
        return $this->getRepository()
            ->getCategoryProductList($productAbstractIds);
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

            $payloadTransfer->setProductLists($lists);
        }

        return $payloadTransfers;
    }
}
