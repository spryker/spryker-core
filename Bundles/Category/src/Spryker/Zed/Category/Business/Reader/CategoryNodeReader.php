<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Reader;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryNodeReader implements CategoryNodeReaderInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllNodesByIdCategory(int $idCategory): array
    {
        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->addIdCategory($idCategory);

        return $this->categoryRepository
            ->getCategoryNodesByCriteria($categoryNodeCriteriaTransfer)
            ->getNodes()
            ->getArrayCopy();
    }
}
