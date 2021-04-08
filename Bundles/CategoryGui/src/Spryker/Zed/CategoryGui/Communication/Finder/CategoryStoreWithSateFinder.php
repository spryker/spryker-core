<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Finder;

use Generated\Shared\Transfer\StoreWithStateCollectionTransfer;
use Spryker\Zed\CategoryGui\Communication\Mapper\CategoryStoreWithStateMapperInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToStoreFacadeInterface;

class CategoryStoreWithSateFinder implements CategoryStoreWithSateFinderInterface
{
    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Communication\Mapper\CategoryStoreWithStateMapperInterface
     */
    protected $categoryStoreWithStateMapper;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CategoryGui\Communication\Mapper\CategoryStoreWithStateMapperInterface $categoryStoreWithStateMapper
     */
    public function __construct(
        CategoryGuiToCategoryFacadeInterface $categoryFacade,
        CategoryGuiToStoreFacadeInterface $storeFacade,
        CategoryStoreWithStateMapperInterface $categoryStoreWithStateMapper
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->storeFacade = $storeFacade;
        $this->categoryStoreWithStateMapper = $categoryStoreWithStateMapper;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\StoreWithStateCollectionTransfer
     */
    public function getAllStoresWithStateByIdCategoryNode(int $idCategoryNode): StoreWithStateCollectionTransfer
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $categoryStoreRelationTransfers = $this->categoryFacade
            ->getCategoryStoreRelationByIdCategoryNode($idCategoryNode)
            ->getStores()
            ->getArrayCopy();

        return $this->categoryStoreWithStateMapper
            ->mapStoresWithCategoryStoreRelatedTransfersToStoreWithStateCollection($storeTransfers, $categoryStoreRelationTransfers);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return int[]
     */
    public function getInactiveStoreIdsByIdCategoryNode(int $idCategoryNode): array
    {
        $storeWithEnablementCollectionTransfer = $this->getAllStoresWithStateByIdCategoryNode($idCategoryNode);

        $inactiveStoreIds = [];
        foreach ($storeWithEnablementCollectionTransfer->getStoresWithState() as $storeWithStateTransfer) {
            if (!$storeWithStateTransfer->getIsActive()) {
                $inactiveStoreIds[] = $storeWithStateTransfer->getIdStoreOrFail();
            }
        }

        return $inactiveStoreIds;
    }
}
