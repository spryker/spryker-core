<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Finder;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;

class CategoryFinder implements CategoryFinderInterface
{
    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CategoryGuiToCategoryFacadeInterface $categoryFacade,
        CategoryGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryWithLocalizedAttributesById(int $idCategory): ?CategoryTransfer
    {
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($idCategory)
            ->setWithChildrenRecursively(true);

        $categoryTransfer = $this->categoryFacade->findCategory($categoryCriteriaTransfer);
        if ($categoryTransfer === null) {
            return null;
        }

        return $this->addLocalizedAttributeTransfers($categoryTransfer);
    }

    /**
     * @param int|null $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getCategoryNodes(?int $idCategory = null): array
    {
        $nodeTransfers = [];

        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $categoryCollectionTransfer = $this->categoryFacade->getAllCategoryCollection($localeTransfer);

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            if ($categoryTransfer->getIdCategory() === $idCategory) {
                continue;
            }

            $nodeTransfers = $this->extractNodesFromCategory($nodeTransfers, $categoryTransfer);
        }

        return $nodeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function addLocalizedAttributeTransfers(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $categoryLocaleIds = $this->extractCategoryLocaleIds($categoryTransfer);

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            if (in_array($localeTransfer->getIdLocale(), $categoryLocaleIds, true)) {
                continue;
            }

            $categoryTransfer->addLocalizedAttributes(
                (new CategoryLocalizedAttributesTransfer())->setLocale($localeTransfer)
            );
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return int[]
     */
    protected function extractCategoryLocaleIds(CategoryTransfer $categoryTransfer): array
    {
        $categoryLocaleIds = [];
        foreach ($categoryTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $categoryLocaleIds[] = $localizedAttribute->getLocale()->getIdLocale();
        }

        return $categoryLocaleIds;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    protected function extractNodesFromCategory(array $nodeTransfers, CategoryTransfer $categoryTransfer): array
    {
        foreach ($categoryTransfer->getNodeCollection()->getNodes() as $nodeTransfer) {
            $nodeTransfers[] = (new NodeTransfer())
                ->setPath('/' . $nodeTransfer->getPath())
                ->setIdCategoryNode($nodeTransfer->getIdCategoryNode())
                ->setName($categoryTransfer->getName());
        }

        return $nodeTransfers;
    }
}
