<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Business\PluginExecutor\CategoryPluginExecutorInterface;
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
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $repository
     * @param \Spryker\Zed\Category\Business\PluginExecutor\CategoryPluginExecutorInterface $categoryPluginExecutor
     */
    public function __construct(
        CategoryRepositoryInterface $repository,
        CategoryPluginExecutorInterface $categoryPluginExecutor
    ) {
        $this->repository = $repository;
        $this->categoryPluginExecutor = $categoryPluginExecutor;
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
}
