<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Finder;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToStoreFacadeInterface;

class InactiveCategoryStoreFinder implements InactiveCategoryStoreFinderInterface
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
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        CategoryGuiToCategoryFacadeInterface $categoryFacade,
        CategoryGuiToStoreFacadeInterface $storeFacade
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param int|null $idCategoryNode
     *
     * @return int[]
     */
    public function findStoresByIdCategoryNode(?int $idCategoryNode): array
    {
        if ($idCategoryNode === null) {
            return [];
        }

        $parentStoresIds = $this->extractStoreIds(
            $this->categoryFacade
                ->getCategoryStoreRelationByIdCategoryNode($idCategoryNode)
                ->getStores()
                ->getArrayCopy()
        );
        if ($parentStoresIds === []) {
            return [];
        }

        $allStoresIds = $this->extractStoreIds($this->storeFacade->getAllStores());

        return array_diff($allStoresIds, $parentStoresIds);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return int[]
     */
    protected function extractStoreIds(array $storeTransfers): array
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStoreOrFail();
        }, $storeTransfers);
    }
}
