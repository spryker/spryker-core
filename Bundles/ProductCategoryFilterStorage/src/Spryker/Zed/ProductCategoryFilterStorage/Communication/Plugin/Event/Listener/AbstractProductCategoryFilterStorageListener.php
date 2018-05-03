<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductCategoryFilterStorageTransfer;
use Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Communication\ProductCategoryFilterStorageCommunicationFactory getFactory()
 */
class AbstractProductCategoryFilterStorageListener extends AbstractPlugin
{
    const FK_CATEGORY = 'fkCategory';
    const FILTER_DATA = 'filterData';

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    protected function publish(array $categoryIds)
    {
        $productCategoryFilters = $this->getQueryContainer()->queryProductCategoryByIdCategories($categoryIds)
            ->find()
            ->toKeyValue(static::FK_CATEGORY, static::FILTER_DATA);

        $categoryFilterStorageEntitiesByCategoryIds = $this->findProductCategoryFilterStorageEntitiesByCategoryIds($categoryIds);
        $this->storeData($productCategoryFilters, $categoryFilterStorageEntitiesByCategoryIds);
    }

    /**
     * @param array $productCategoryFilters
     * @param array $categoryFilterStorageEntitiesByCategoryIds
     *
     * @return void
     */
    protected function storeData(array $productCategoryFilters, array $categoryFilterStorageEntitiesByCategoryIds)
    {
        foreach ($productCategoryFilters as $idCategory => $filterData) {
            $filterDataArray = $this->getFactory()->getUtilEncoding()->decodeJson($filterData, true);
            if (isset($categoryFilterStorageEntitiesByCategoryIds[$idCategory])) {
                $this->storeDataSet($idCategory, $filterDataArray, $categoryFilterStorageEntitiesByCategoryIds[$idCategory]);
            } else {
                $this->storeDataSet($idCategory, $filterDataArray);
            }
        }
    }

    /**
     * @param int $idCategory
     * @param array $filterData
     * @param \Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorage|null $spyProductCategoryFilterStorage
     *
     * @return void
     */
    protected function storeDataSet($idCategory, array $filterData, ?SpyProductCategoryFilterStorage $spyProductCategoryFilterStorage = null)
    {
        if ($spyProductCategoryFilterStorage === null) {
            $spyProductCategoryFilterStorage = new SpyProductCategoryFilterStorage();
        }

        if (empty($filterData)) {
            if (!$spyProductCategoryFilterStorage->isNew()) {
                $spyProductCategoryFilterStorage->delete();
            }

            return;
        }

        $spyProductCategoryFilterStorageTransfer = new ProductCategoryFilterStorageTransfer();
        $spyProductCategoryFilterStorageTransfer->setIdCategory($idCategory);
        $spyProductCategoryFilterStorageTransfer->setFilterData($filterData);

        $spyProductCategoryFilterStorage->setFkCategory($idCategory);
        $spyProductCategoryFilterStorage->setData($spyProductCategoryFilterStorageTransfer->toArray());
        $spyProductCategoryFilterStorage->setStore($this->getStoreName());
        $spyProductCategoryFilterStorage->save();
    }

    /**
     * @param array $idCategories
     *
     * @return array
     */
    protected function findProductCategoryFilterStorageEntitiesByCategoryIds(array $idCategories)
    {
        $productCategoryFilterStorageEntities = $this->getQueryContainer()->queryProductCategoryFilterStorageByFkCategories($idCategories)->find();
        $productAbstractStorageEntitiesByIdCategory = [];
        foreach ($productCategoryFilterStorageEntities as $productCategoryFilterStorageEntity) {
            $productAbstractStorageEntitiesByIdCategory[$productCategoryFilterStorageEntity->getFkCategory()] = $productCategoryFilterStorageEntity;
        }

        return $productAbstractStorageEntitiesByIdCategory;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
