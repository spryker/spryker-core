<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Storage;

use Generated\Shared\Transfer\CategoryTreeStorageTransfer;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CategoryTreeStorage implements CategoryTreeStorageInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface
     */
    protected $categoryStorageRepository;

    /**
     * @var \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface
     */
    protected $categoryStorageNodeTreeBuilder;

    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitize;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface $categoryStorageRepository
     * @param \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder
     * @param \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface $utilSanitize
     */
    public function __construct(
        CategoryStorageQueryContainerInterface $queryContainer,
        CategoryStorageRepositoryInterface $categoryStorageRepository,
        CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder,
        CategoryStorageToUtilSanitizeServiceInterface $utilSanitize
    ) {
        $this->queryContainer = $queryContainer;
        $this->categoryStorageRepository = $categoryStorageRepository;
        $this->categoryStorageNodeTreeBuilder = $categoryStorageNodeTreeBuilder;
        $this->utilSanitize = $utilSanitize;
    }

    /**
     * @return void
     */
    public function publish(): void
    {
        $categoryTrees = $this->getCategoryNodeStorageTransferTrees();
        $categoryTreeStorageEntities = $this->findCategoryTreeStorageEntities();
        $categoryTreeStorageEntitiesIndexedByStoreAndLocale = $this->indexCategoryTreeStorageEntitiesByStoreAndLocale($categoryTreeStorageEntities);

        $this->storeData($categoryTrees, $categoryTreeStorageEntitiesIndexedByStoreAndLocale);
    }

    /**
     * @return void
     */
    public function unpublish(): void
    {
        $spyCategoryMenuTranslationStorageEntities = $this->findCategoryTreeStorageEntities();
        foreach ($spyCategoryMenuTranslationStorageEntities as $spyCategoryMenuTranslationStorageEntity) {
            $spyCategoryMenuTranslationStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[][][] $categoryNodeStorageTransferTreesIndexedByStoreAndLocale
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage[][] $categoryTreeStorageEntitiesIndexedByStoreAndLocale
     *
     * @return void
     */
    protected function storeData(
        array $categoryNodeStorageTransferTreesIndexedByStoreAndLocale,
        array $categoryTreeStorageEntitiesIndexedByStoreAndLocale
    ): void {
        foreach ($categoryNodeStorageTransferTreesIndexedByStoreAndLocale as $storeName => $categoryNodeStorageTransferTreesIndexedByLocale) {
            foreach ($categoryNodeStorageTransferTreesIndexedByLocale as $localeName => $categoryNodeStorageTransferTrees) {
                $this->storeCategoryNodeStorageTransferTreesForStoreAndLocale(
                    $categoryNodeStorageTransferTrees,
                    $categoryTreeStorageEntitiesIndexedByStoreAndLocale,
                    $storeName,
                    $localeName
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransferTrees
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage[][] $categoryTreeStorageEntitiesIndexedByStoreAndLocale
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function storeCategoryNodeStorageTransferTreesForStoreAndLocale(
        array $categoryNodeStorageTransferTrees,
        array $categoryTreeStorageEntitiesIndexedByStoreAndLocale,
        string $storeName,
        string $localeName
    ): void {
        if (!isset($categoryTreeStorageEntitiesIndexedByStoreAndLocale[$storeName][$localeName])) {
            $this->storeDataSet(
                $categoryNodeStorageTransferTrees,
                $storeName,
                $localeName
            );

            return;
        }

        $this->storeDataSet(
            $categoryNodeStorageTransferTrees,
            $storeName,
            $localeName,
            $categoryTreeStorageEntitiesIndexedByStoreAndLocale[$storeName][$localeName],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     * @param string $storeName
     * @param string $localeName
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage|null $categoryTreeStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(
        array $categoryNodeStorageTransfers,
        string $storeName,
        string $localeName,
        ?SpyCategoryTreeStorage $categoryTreeStorageEntity = null
    ): void {
        if ($categoryTreeStorageEntity === null) {
            $categoryTreeStorageEntity = new SpyCategoryTreeStorage();
        }

        $categoryTreeStorageTransfer = new CategoryTreeStorageTransfer();
        foreach ($categoryNodeStorageTransfers as $categoryNodeStorageTransfer) {
            $categoryTreeStorageTransfer->addCategoryNodeStorage($categoryNodeStorageTransfer);
        }

        $categoryTreeStorageData = $this->utilSanitize->arrayFilterRecursive($categoryTreeStorageTransfer->toArray());
        $categoryTreeStorageEntity->setLocale($localeName);
        $categoryTreeStorageEntity->setStore($storeName);
        $categoryTreeStorageEntity->setData($categoryTreeStorageData);
        $categoryTreeStorageEntity->save();
    }

    /**
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage[]
     */
    protected function findCategoryTreeStorageEntities(): array
    {
        return $this->queryContainer
            ->queryCategoryStorage()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage[] $categoryTreeStorageEntities
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage[][]
     */
    protected function indexCategoryTreeStorageEntitiesByStoreAndLocale(array $categoryTreeStorageEntities): array
    {
        $categoryStorageEntitiesIndexedByStoreAndLocale = [];
        foreach ($categoryTreeStorageEntities as $categoryTreeStorageEntity) {
            $categoryStorageEntitiesIndexedByStoreAndLocale[$categoryTreeStorageEntity->getStore()][$categoryTreeStorageEntity->getLocale()] = $categoryTreeStorageEntity;
        }

        return $categoryStorageEntitiesIndexedByStoreAndLocale;
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[][][]
     */
    protected function getCategoryNodeStorageTransferTrees(): array
    {
        $rootCategory = $this->queryContainer->queryCategoryRoot()->findOne();
        if (!$rootCategory) {
            return [];
        }

        $categoryNodeTransfers = $this->categoryStorageRepository->getCategoryNodesByCategoryNodeIds([$rootCategory->getIdCategoryNode()]);

        return $this->categoryStorageNodeTreeBuilder->buildCategoryNodeStorageTransferTreesForLocaleAndStore(
            [$rootCategory->getIdCategoryNode()],
            $categoryNodeTransfers
        );
    }
}
