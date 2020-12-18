<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Storage;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface;

class CategoryNodeStorage implements CategoryNodeStorageInterface
{
    /**
     * @var \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface
     */
    protected $categoryStorageNodeTreeBuilder;

    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface $utilSanitizeService
     */
    public function __construct(
        CategoryStorageQueryContainerInterface $queryContainer,
        CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder,
        CategoryStorageToCategoryFacadeInterface $categoryFacade,
        CategoryStorageToUtilSanitizeServiceInterface $utilSanitizeService
    ) {
        $this->queryContainer = $queryContainer;
        $this->categoryStorageNodeTreeBuilder = $categoryStorageNodeTreeBuilder;
        $this->categoryFacade = $categoryFacade;
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds): void
    {
        $nodeTransfers = $this->categoryFacade->getAllCategoryNodeTreeElementsByCategoryNodeIds($categoryNodeIds);
        $categoryNodeStorageEntities = $this->findCategoryNodeStorageEntitiesByCategoryNodeIds($categoryNodeIds);

        if (!$nodeTransfers) {
            $this->deleteStorageData($categoryNodeStorageEntities);
        }

        $categoryNodeStorageEntitiesIndexedByStoreAndLocale = $this->indexCategoryNodeStorageEntitiesByStoreAndLocale(
            $categoryNodeStorageEntities
        );
        $categoryNodeStorageTransferTreesIndexedByLocaleAndStore = $this->categoryStorageNodeTreeBuilder
            ->buildCategoryNodeStorageTransferTreesForLocaleAndStore(
                $categoryNodeIds,
                $nodeTransfers
            );

        $this->storeData($categoryNodeStorageTransferTreesIndexedByLocaleAndStore, $categoryNodeStorageEntitiesIndexedByStoreAndLocale);
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds): void
    {
        $categoryNodeStorageEntities = $this->findCategoryNodeStorageEntitiesByCategoryNodeIds($categoryNodeIds);

        $this->deleteStorageData($categoryNodeStorageEntities);
    }

    /**
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage[] $categoryNodeStorageEntities
     *
     * @return void
     */
    protected function deleteStorageData(array $categoryNodeStorageEntities): void
    {
        foreach ($categoryNodeStorageEntities as $categoryNodeStorageEntity) {
            $categoryNodeStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[][][] $categoryNodeStorageTransferTreesIndexedByStoreAndLocale
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage[][][] $categoryNodeStorageEntities
     *
     * @return void
     */
    protected function storeData(array $categoryNodeStorageTransferTreesIndexedByStoreAndLocale, array $categoryNodeStorageEntities): void
    {
        foreach ($categoryNodeStorageTransferTreesIndexedByStoreAndLocale as $storeName => $categoryNodeStorageTransferTreesIndexedByLocale) {
            foreach ($categoryNodeStorageTransferTreesIndexedByLocale as $localeName => $categoryNodeStorageTransferTrees) {
                $this->storeCategoryNodeStorageTransferTreesForStoreAndLocale(
                    $categoryNodeStorageTransferTrees,
                    $categoryNodeStorageEntities,
                    $storeName,
                    $localeName
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransferTrees
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage[][][] $categoryNodeStorageEntities
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function storeCategoryNodeStorageTransferTreesForStoreAndLocale(
        array $categoryNodeStorageTransferTrees,
        array $categoryNodeStorageEntities,
        string $storeName,
        string $localeName
    ): void {
        foreach ($categoryNodeStorageTransferTrees as $idCategoryNode => $categoryNodeStorageTransfer) {
            if (!isset($categoryNodeStorageEntities[$storeName][$localeName][$idCategoryNode])) {
                $this->storeDataSet($categoryNodeStorageTransfer, $storeName, $localeName);

                continue;
            }

            $this->storeDataSet(
                $categoryNodeStorageTransfer,
                $storeName,
                $localeName,
                $categoryNodeStorageEntities[$storeName][$localeName][$idCategoryNode]
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param string $storeName
     * @param string $localeName
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage|null $spyCategoryNodeStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer,
        string $storeName,
        string $localeName,
        ?SpyCategoryNodeStorage $spyCategoryNodeStorageEntity = null
    ): void {
        if ($spyCategoryNodeStorageEntity === null) {
            $spyCategoryNodeStorageEntity = new SpyCategoryNodeStorage();
        }

        if (!$categoryNodeStorageTransfer->getIsActive()) {
            if (!$spyCategoryNodeStorageEntity->isNew()) {
                $spyCategoryNodeStorageEntity->delete();
            }

            return;
        }

        $categoryNodeStorageData = $this->utilSanitizeService->arrayFilterRecursive($categoryNodeStorageTransfer->toArray());
        $spyCategoryNodeStorageEntity->setFkCategoryNode($categoryNodeStorageTransfer->getNodeId());
        $spyCategoryNodeStorageEntity->setData($categoryNodeStorageData);
        $spyCategoryNodeStorageEntity->setLocale($localeName);
        $spyCategoryNodeStorageEntity->setStore($storeName);
        $spyCategoryNodeStorageEntity->save();
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage[]
     */
    protected function findCategoryNodeStorageEntitiesByCategoryNodeIds(array $categoryNodeIds): array
    {
        return $this->queryContainer
            ->queryCategoryNodeStorageByIds($categoryNodeIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage[] $categoryNodeStorageEntities
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage[][][]
     */
    protected function indexCategoryNodeStorageEntitiesByStoreAndLocale(array $categoryNodeStorageEntities): array
    {
        $categoryNodeStorageEntitiesIndexedByStoreAndLocale = [];
        foreach ($categoryNodeStorageEntities as $categoryNodeStorageEntity) {
            $categoryNodeStorageEntitiesIndexedByStoreAndLocale[$categoryNodeStorageEntity->getStore()][$categoryNodeStorageEntity->getLocale()][$categoryNodeStorageEntity->getFkCategoryNode()] = $categoryNodeStorageEntity;
        }

        return $categoryNodeStorageEntitiesIndexedByStoreAndLocale;
    }
}
