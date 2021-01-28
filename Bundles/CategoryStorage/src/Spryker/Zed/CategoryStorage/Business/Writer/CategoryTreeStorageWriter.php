<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Writer;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTreeStorageTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface;

class CategoryTreeStorageWriter implements CategoryTreeStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface
     */
    protected $categoryStorageRepository;

    /**
     * @var \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface
     */
    protected $categoryStorageEntityManager;

    /**
     * @var \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface
     */
    protected $categoryStorageNodeTreeBuilder;

    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface $categoryStorageRepository
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface $categoryStorageEntityManager
     * @param \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface $categoryFacade
     */
    public function __construct(
        CategoryStorageRepositoryInterface $categoryStorageRepository,
        CategoryStorageEntityManagerInterface $categoryStorageEntityManager,
        CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder,
        CategoryStorageToCategoryFacadeInterface $categoryFacade
    ) {
        $this->categoryStorageRepository = $categoryStorageRepository;
        $this->categoryStorageEntityManager = $categoryStorageEntityManager;
        $this->categoryStorageNodeTreeBuilder = $categoryStorageNodeTreeBuilder;
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @return void
     */
    public function writeCategoryTreeStorageCollection(): void
    {
        $categoryTrees = $this->getCategoryNodeStorageTransferTrees();

        $this->storeData($categoryTrees);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[][][] $categoryNodeStorageTransferTreesIndexedByStoreAndLocale
     *
     * @return void
     */
    protected function storeData(array $categoryNodeStorageTransferTreesIndexedByStoreAndLocale): void
    {
        foreach ($categoryNodeStorageTransferTreesIndexedByStoreAndLocale as $storeName => $categoryNodeStorageTransferTreesIndexedByLocale) {
            foreach ($categoryNodeStorageTransferTreesIndexedByLocale as $localeName => $categoryNodeStorageTransferTrees) {
                $this->storeDataSet($categoryNodeStorageTransferTrees, $storeName, $localeName);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function storeDataSet(
        array $categoryNodeStorageTransfers,
        string $storeName,
        string $localeName
    ): void {
        if ($categoryNodeStorageTransfers === []) {
            return;
        }

        $categoryTreeStorageTransfer = (new CategoryTreeStorageTransfer())
            ->setCategoryNodesStorage(
                $categoryNodeStorageTransfers[array_key_first($categoryNodeStorageTransfers)]->getChildren()
            )
            ->setLocale($localeName)
            ->setStore($storeName);

        $this->categoryStorageEntityManager->saveCategoryTreeStorage($categoryTreeStorageTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[][][]
     */
    protected function getCategoryNodeStorageTransferTrees(): array
    {
        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->setIsRoot(true)
            ->setWithRelations(true);

        $nodeCollectionTransfer = $this->categoryFacade->getCategoryNodesByCriteria($categoryNodeCriteriaTransfer);

        if (!$nodeCollectionTransfer->getNodes()->count()) {
            return [];
        }

        $categoryNodeIds = $this->getCategoryNodeIdsFromNodeCollectionTransfer($nodeCollectionTransfer);

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->setIsActive(true)
            ->setIsInMenu(true)
            ->setCategoryNodeIds($categoryNodeIds);

        $categoryNodeTransfers = $this->categoryFacade->getCategoryNodesWithRelativeNodesByCriteria(
            $categoryNodeCriteriaTransfer
        );

        return $this->categoryStorageNodeTreeBuilder->buildCategoryNodeStorageTransferTreesForLocaleAndStore(
            $categoryNodeIds,
            $categoryNodeTransfers
        );
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return int[]
     */
    protected function getCategoryNodeIdsFromNodeCollectionTransfer(NodeCollectionTransfer $nodeCollectionTransfer): array
    {
        return array_map(function (NodeTransfer $nodeTransfer): int {
            return $nodeTransfer->getIdCategoryNodeOrFail();
        }, $nodeCollectionTransfer->getNodes()->getArrayCopy());
    }
}
