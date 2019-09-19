<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Business\Storage;

use Generated\Shared\Transfer\ProductCategoryFilterStorageTransfer;
use Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorage;
use Spryker\Zed\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToUtilEncodingInterface;
use Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStorageQueryContainerInterface;

class ProductCategoryFilterStorageWriter implements ProductCategoryFilterStorageWriterInterface
{
    public const FK_CATEGORY = 'fkCategory';
    public const FILTER_DATA = 'filterData';

    /**
     * @var \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToUtilEncodingInterface $utilEncodingService
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductCategoryFilterStorageQueryContainerInterface $queryContainer,
        ProductCategoryFilterStorageToUtilEncodingInterface $utilEncodingService,
        $isSendingToQueue
    ) {
        $this->queryContainer = $queryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds)
    {
        $productCategoryFilters = $this->queryContainer->queryProductCategoryByIdCategories($categoryIds)
            ->find()
            ->toKeyValue(static::FK_CATEGORY, static::FILTER_DATA);

        $categoryFilterStorageEntitiesByCategoryIds = $this->findProductCategoryFilterStorageEntitiesByCategoryIds($categoryIds);
        $this->storeData($productCategoryFilters, $categoryFilterStorageEntitiesByCategoryIds);
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublish(array $categoryIds)
    {
        $categoryFilterStorageEntities = $this->findProductCategoryFilterStorageEntitiesByCategoryIds($categoryIds);
        foreach ($categoryFilterStorageEntities as $categoryFilterStorageEntity) {
            $categoryFilterStorageEntity->delete();
        }
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
            $filterDataArray = $this->utilEncodingService->decodeJson($filterData, true);
            if (isset($categoryFilterStorageEntitiesByCategoryIds[$idCategory])) {
                $this->storeDataSet($idCategory, $filterDataArray, $categoryFilterStorageEntitiesByCategoryIds[$idCategory]);

                continue;
            }

            $this->storeDataSet($idCategory, $filterDataArray);
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
        $spyProductCategoryFilterStorage->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductCategoryFilterStorage->save();
    }

    /**
     * @param array $idCategories
     *
     * @return array
     */
    protected function findProductCategoryFilterStorageEntitiesByCategoryIds(array $idCategories)
    {
        $productCategoryFilterStorageEntities = $this->queryContainer->queryProductCategoryFilterStorageByFkCategories($idCategories)->find();
        $productAbstractStorageEntitiesByIdCategory = [];
        foreach ($productCategoryFilterStorageEntities as $productCategoryFilterStorageEntity) {
            $productAbstractStorageEntitiesByIdCategory[$productCategoryFilterStorageEntity->getFkCategory()] = $productCategoryFilterStorageEntity;
        }

        return $productAbstractStorageEntitiesByIdCategory;
    }
}
