<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Handler;

use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Service\CategoryGuiToUtilEncodingServiceInterface;

class CategoryReSortHandler implements CategoryReSortHandlerInterface
{
    protected const KEY_ID = 'id';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Service\CategoryGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryGui\Dependency\Service\CategoryGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        CategoryGuiToCategoryFacadeInterface $categoryFacade,
        CategoryGuiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $categoryNodesData
     *
     * @return void
     */
    public function updateCategoryNodeOrder(string $categoryNodesData): void
    {
        $categoryNodesToReorder = $this->utilEncodingService->decodeJson($categoryNodesData, true);

        $positionCursor = count($categoryNodesToReorder);

        foreach ($categoryNodesToReorder as $nodeData) {
            $idCategoryNode = (int)$nodeData[static::KEY_ID];
            $this->categoryFacade->updateCategoryNodeOrder($idCategoryNode, $positionCursor);

            $positionCursor--;
        }
    }
}
