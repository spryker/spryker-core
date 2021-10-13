<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Finder;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\CategoryGui\Communication\Expander\CategoryExpanderInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;

class CategoryFinder implements CategoryFinderInterface
{
    /**
     * @var string
     */
    protected const PATH_DELIMITER = '/';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Communication\Expander\CategoryExpanderInterface
     */
    protected $categoryExpander;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\CategoryGui\Communication\Expander\CategoryExpanderInterface $categoryExpander
     */
    public function __construct(
        CategoryGuiToCategoryFacadeInterface $categoryFacade,
        CategoryGuiToLocaleFacadeInterface $localeFacade,
        CategoryExpanderInterface $categoryExpander
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
        $this->categoryExpander = $categoryExpander;
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryByIdCategoryAndLocale(int $idCategory, ?LocaleTransfer $localeTransfer = null): ?CategoryTransfer
    {
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($idCategory)
            ->setWithChildrenRecursively(true);

        if ($localeTransfer !== null) {
            $categoryCriteriaTransfer = $categoryCriteriaTransfer
                ->setLocaleName($localeTransfer->getLocaleName());
        }

        return $this->categoryFacade->findCategory($categoryCriteriaTransfer);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryWithLocalizedAttributesById(int $idCategory): ?CategoryTransfer
    {
        $categoryTransfer = $this->findCategoryByIdCategoryAndLocale($idCategory);
        if ($categoryTransfer === null) {
            return null;
        }

        return $this->categoryExpander->expandCategoryWithLocalizedAttributes($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findParentCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer): ?CategoryTransfer
    {
        $parentCategoryNode = $categoryTransfer->getParentCategoryNode();
        if ($parentCategoryNode === null) {
            return null;
        }

        return $this->findCategoryByIdCategoryAndLocale(
            $parentCategoryNode->getFkCategoryOrFail(),
            $localeTransfer
        );
    }

    /**
     * @param int|null $idCategory
     *
     * @return array<\Generated\Shared\Transfer\NodeTransfer>
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
     * @param array<\Generated\Shared\Transfer\NodeTransfer> $nodeTransfers
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array<\Generated\Shared\Transfer\NodeTransfer>
     */
    protected function extractNodesFromCategory(array $nodeTransfers, CategoryTransfer $categoryTransfer): array
    {
        foreach ($categoryTransfer->getNodeCollectionOrFail()->getNodes() as $nodeTransfer) {
            $nodeTransfers[] = (new NodeTransfer())
                ->setPath(static::PATH_DELIMITER . $nodeTransfer->getPath())
                ->setIdCategoryNode($nodeTransfer->getIdCategoryNode())
                ->setName($categoryTransfer->getName());
        }

        return $nodeTransfers;
    }
}
