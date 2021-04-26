<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTreeStorageTransfer;
use Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface;

class CategoryTreeStorageWriter implements CategoryTreeStorageWriterInterface
{
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
     * @var \Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface
     */
    protected $categoryNodeExtractor;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface $categoryStorageEntityManager
     * @param \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface $categoryNodeExtractor
     */
    public function __construct(
        CategoryStorageEntityManagerInterface $categoryStorageEntityManager,
        CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder,
        CategoryStorageToCategoryFacadeInterface $categoryFacade,
        CategoryNodeExtractorInterface $categoryNodeExtractor
    ) {
        $this->categoryStorageEntityManager = $categoryStorageEntityManager;
        $this->categoryStorageNodeTreeBuilder = $categoryStorageNodeTreeBuilder;
        $this->categoryFacade = $categoryFacade;
        $this->categoryNodeExtractor = $categoryNodeExtractor;
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
        // TODO: was it tested????
        $categoryNodeStorages = new ArrayObject();

        if ($categoryNodeStorageTransfers !== []) {
            $categoryNodeStorages = $categoryNodeStorageTransfers[array_key_first($categoryNodeStorageTransfers)]->getChildren();
        }

        $categoryTreeStorageTransfer = (new CategoryTreeStorageTransfer())
            ->setCategoryNodesStorage($categoryNodeStorages)
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

        $nodeCollectionTransfer = $this->categoryFacade->getCategoryNodes($categoryNodeCriteriaTransfer);

        if (!$nodeCollectionTransfer->getNodes()->count()) {
            return [];
        }

        $categoryNodeIds = $this->categoryNodeExtractor->extractCategoryNodeIdsFromNodeCollection($nodeCollectionTransfer);

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->setIsActive(true)
            ->setIsInMenu(true)
            ->setCategoryNodeIds($categoryNodeIds);

        $categoryNodeTransfers = $this->categoryFacade
            ->getCategoryNodesWithRelativeNodes($categoryNodeCriteriaTransfer)
            ->getNodes()
            ->getArrayCopy();

        return $this->categoryStorageNodeTreeBuilder->buildCategoryNodeStorageTransferTreesForLocaleAndStore(
            $categoryNodeIds,
            $categoryNodeTransfers
        );
    }
}
