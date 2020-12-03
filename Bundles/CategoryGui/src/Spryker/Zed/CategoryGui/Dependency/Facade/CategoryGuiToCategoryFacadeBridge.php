<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Dependency\Facade;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

class CategoryGuiToCategoryFacadeBridge implements CategoryGuiToCategoryFacadeInterface
{
    /**
     * @var \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\Category\Business\CategoryFacadeInterface $categoryFacade
     */
    public function __construct($categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param string $name
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function checkSameLevelCategoryByNameExists(string $name, CategoryTransfer $categoryTransfer): bool
    {
        return $this->categoryFacade->checkSameLevelCategoryByNameExists($name, $categoryTransfer);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryById(int $idCategory): ?CategoryTransfer
    {
        return $this->categoryFacade->findCategoryById($idCategory);
    }

    /**
     * @return void
     */
    public function syncCategoryTemplate()
    {
        $this->categoryFacade->syncCategoryTemplate();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $this->categoryFacade->create($categoryTransfer);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllNodesByIdCategory(int $idCategory)
    {
        return $this->categoryFacade->getAllNodesByIdCategory($idCategory);
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete(int $idCategory)
    {
        $this->categoryFacade->delete($idCategory);
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getSubTreeByIdCategoryNodeAndLocale(int $idCategoryNode, LocaleTransfer $localeTransfer)
    {
        return $this->categoryFacade->getSubTreeByIdCategoryNodeAndLocale($idCategoryNode, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $this->categoryFacade->update($categoryTransfer);
    }

    /**
     * @param int $idCategoryNode
     * @param int $position
     *
     * @return void
     */
    public function updateCategoryNodeOrder(int $idCategoryNode, int $position)
    {
        $this->categoryFacade->updateCategoryNodeOrder($idCategoryNode, $position);
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale(int $idCategory, LocaleTransfer $localeTransfer)
    {
        return $this->categoryFacade->getTreeNodeChildrenByIdCategoryAndLocale($idCategory, $localeTransfer);
    }
}
