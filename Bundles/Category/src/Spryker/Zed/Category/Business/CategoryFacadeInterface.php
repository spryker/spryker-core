<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;

interface CategoryFacadeInterface
{

    /**
     * @api
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param int $idNode
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function getNodeById($idNode);

    /**
     * @api
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return int
     */
    public function getCategoryIdentifier($categoryName, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllNodesByIdCategory($idCategory);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getMainNodesByIdCategory($idCategory);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getNotMainNodesByIdCategory($idCategory);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return int
     */
    public function createCategory(CategoryTransfer $category, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $category, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function addCategoryAttribute(CategoryTransfer $category, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategory($idCategory);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode(NodeTransfer $categoryNode, LocaleTransfer $locale, $createUrlPath = true);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function updateCategoryNode(NodeTransfer $categoryNode, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleTransfer $locale, $deleteChildren = false);

    /**
     * @api
     *
     * @return bool
     */
    public function renderCategoryTreeVisual();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getRootNodes();

    /**
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function getTree($idCategory, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function getChildren($idNode, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleTransfer $locale, $excludeStartNode = true);

    /**
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $locale);

    /**
     * @api
     *
     * @return void
     */
    public function rebuildClosureTable();

    /**
     * @api
     *
     * @param array $pathTokens
     *
     * @return string
     */
    public function generatePath(array $pathTokens);

}
