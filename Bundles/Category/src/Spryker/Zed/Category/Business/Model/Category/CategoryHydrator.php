<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\Category;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryHydrator implements CategoryHydratorInterface
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
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function hydrateCategoryCollection(CategoryCollectionTransfer $categoryCollectionTransfer, LocaleTransfer $localeTransfer): void
    {
        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $this->hydrateNodeCollection($categoryTransfer->getNodeCollectionOrFail(), $localeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function hydrateNodeCollection(NodeCollectionTransfer $nodeCollectionTransfer, LocaleTransfer $localeTransfer)
    {
        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $nodeTransfer->setPath($this->categoryRepository->getNodePath($nodeTransfer->getIdCategoryNodeOrFail(), $localeTransfer));
        }
    }
}
