<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Category;

use Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer;
use Spryker\Zed\CategoryGui\Communication\Mapper\CategoryNodeMapperInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Service\CategoryGuiToUtilEncodingServiceInterface;

class CategoryNodeOrderUpdater implements CategoryNodeOrderUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected CategoryGuiToCategoryFacadeInterface $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Service\CategoryGuiToUtilEncodingServiceInterface
     */
    protected CategoryGuiToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Zed\CategoryGui\Communication\Mapper\CategoryNodeMapperInterface
     */
    protected CategoryNodeMapperInterface $categoryNodeMapper;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryGui\Dependency\Service\CategoryGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\CategoryGui\Communication\Mapper\CategoryNodeMapperInterface $categoryNodeMapper
     */
    public function __construct(
        CategoryGuiToCategoryFacadeInterface $categoryFacade,
        CategoryGuiToUtilEncodingServiceInterface $utilEncodingService,
        CategoryNodeMapperInterface $categoryNodeMapper
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->utilEncodingService = $utilEncodingService;
        $this->categoryNodeMapper = $categoryNodeMapper;
    }

    /**
     * @param string $categoryNodesData
     *
     * @return bool
     */
    public function updateCategoryNodeOrder(string $categoryNodesData): bool
    {
        /** @var list<array<string, mixed>> $categoryNodesToReorder */
        $categoryNodesToReorder = $this->utilEncodingService->decodeJson($categoryNodesData, true) ?: [];
        if (!$categoryNodesToReorder) {
            return false;
        }

        $categoryNodeCollectionRequestTransfer = $this->categoryNodeMapper
            ->mapCategoryNodesDataToCategoryNodeCollectionRequestTransfer(
                $categoryNodesToReorder,
                new CategoryNodeCollectionRequestTransfer(),
            )
            ->setIsTransactional(true);

        $categoryNodeCollectionResponseTransfer = $this->categoryFacade->reorderCategoryNodeCollection($categoryNodeCollectionRequestTransfer);

        return !count($categoryNodeCollectionResponseTransfer->getErrors());
    }
}
