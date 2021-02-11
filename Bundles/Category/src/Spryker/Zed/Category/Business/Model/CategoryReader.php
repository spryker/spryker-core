<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Business\PluginExecutor\CategoryPluginExecutorInterface;
use Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryReader implements CategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\Category\Business\PluginExecutor\CategoryPluginExecutorInterface
     */
    protected $categoryPluginExecutor;

    /**
     * @var \Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface
     */
    protected $categoryTreeReader;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $repository
     * @param \Spryker\Zed\Category\Business\PluginExecutor\CategoryPluginExecutorInterface $categoryPluginExecutor
     * @param \Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface $categoryTreeReader
     */
    public function __construct(
        CategoryRepositoryInterface $repository,
        CategoryPluginExecutorInterface $categoryPluginExecutor,
        CategoryTreeReaderInterface $categoryTreeReader
    ) {
        $this->repository = $repository;
        $this->categoryPluginExecutor = $categoryPluginExecutor;
        $this->categoryTreeReader = $categoryTreeReader;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryById(int $idCategory): ?CategoryTransfer
    {
        $categoryTransfer = $this->repository->findCategoryById($idCategory);
        if (!$categoryTransfer) {
            return null;
        }

        return $this->categoryPluginExecutor->executePostReadPlugins($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategory(CategoryCriteriaTransfer $categoryCriteriaTransfer): ?CategoryTransfer
    {
        $categoryTransfer = $this->repository->findCategoryByCriteria($categoryCriteriaTransfer);

        if (!$categoryTransfer) {
            return null;
        }

        if ($categoryCriteriaTransfer->getWithChildren() || $categoryCriteriaTransfer->getWithChildrenRecursively()) {
            $categoryNodeCollectionTransfer = $this->categoryTreeReader->getCategoryNodeCollectionTree(
                $categoryTransfer,
                $categoryCriteriaTransfer
            );

            $categoryTransfer->setNodeCollection($categoryNodeCollectionTransfer);
        }

        return $this->categoryPluginExecutor->executePostReadPlugins($categoryTransfer);
    }
}
