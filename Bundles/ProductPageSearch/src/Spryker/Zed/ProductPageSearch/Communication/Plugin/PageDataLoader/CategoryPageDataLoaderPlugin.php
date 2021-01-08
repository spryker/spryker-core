<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class CategoryPageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $loadTransfer)
    {
        $payloadTransfers = $this->setProductCategories($loadTransfer->getProductAbstractIds(), $loadTransfer->getPayloadTransfers());
        $loadTransfer->setPayloadTransfers($payloadTransfers);

        return $loadTransfer;
    }

    /**
     * @param int[] $productAbstractIds
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     *
     * @return array
     */
    protected function setProductCategories(array $productAbstractIds, array $payloadTransfers): array
    {
        $mappedProductCategoryEntities = $this->getMappedProductCategoryEntitiesByIdProductAbstractAndStore($productAbstractIds);

        foreach ($payloadTransfers as $payloadTransfer) {
            if (!isset($mappedProductCategoryEntities[$payloadTransfer->getIdProductAbstract()])) {
                continue;
            }

            $categories = $mappedProductCategoryEntities[$payloadTransfer->getIdProductAbstract()];
            $payloadTransfer->setCategories($categories);
        }

        return $payloadTransfers;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[][]
     */
    protected function getMappedProductCategoryEntitiesByIdProductAbstractAndStore(array $productAbstractIds): array
    {
        $productCategoryEntityCollection = $this->getQueryContainer()
            ->queryProductCategoriesByProductAbstractIds($productAbstractIds)
            ->useSpyCategoryQuery(null, Criteria::LEFT_JOIN)
                ->useSpyCategoryStoreQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinSpyStore()
                ->endUse()
            ->endUse()
            ->find();

        $mappedProductCategoryEntities = [];

        foreach ($productCategoryEntityCollection as $productCategoryEntity) {
            $mappedProductCategoryEntities = $this->mapProductCategoryEntityByIdProductAbstractAndStore(
                $productCategoryEntity,
                $mappedProductCategoryEntities
            );
        }

        return $mappedProductCategoryEntities;
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $productCategoryEntity
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[][] $productCategoryEntities
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[][]
     */
    protected function mapProductCategoryEntityByIdProductAbstractAndStore(
        SpyProductCategory $productCategoryEntity,
        array $productCategoryEntities
    ): array {
        foreach ($productCategoryEntity->getSpyCategory()->getSpyCategoryStores() as $categoryStoreEntity) {
            $idProductAbstract = $productCategoryEntity->getFkProductAbstract();
            $storeName = $categoryStoreEntity->getSpyStore()->getName();

            $productCategoryEntities[$idProductAbstract][$storeName][] = $productCategoryEntity;
        }

        return $productCategoryEntities;
    }
}
