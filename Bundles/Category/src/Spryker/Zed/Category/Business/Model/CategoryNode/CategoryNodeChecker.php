<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryNode;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryNodeChecker implements CategoryNodeCheckerInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->queryContainer = $queryContainer;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param string $name
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function hasFirstLevelChildrenByName(string $name, CategoryTransfer $categoryTransfer): bool
    {
        $exists = $this->queryContainer
            ->queryFirstLevelChildrenByName(
                $categoryTransfer->getParentCategoryNode()->getIdCategoryNode(),
                $name
            )
            ->exists();

        if ($exists) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function checkSameLevelCategoryByNameExists(string $name, CategoryTransfer $categoryTransfer): bool
    {
        return $this->categoryRepository->checkSameLevelCategoryByNameExists($name, $categoryTransfer);
    }
}
