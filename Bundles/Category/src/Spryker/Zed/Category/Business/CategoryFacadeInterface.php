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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleTransfer $localeTransfer);

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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $localeTransfer);

    /**
     * @api
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function getCategoryIdentifier($categoryName, LocaleTransfer $localeTransfer);

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
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return int
     */
    public function createCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer = null);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer = null);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function addCategoryAttribute(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer);

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
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode(NodeTransfer $nodeTransfer, LocaleTransfer $localeTransfer = null, $createUrlPath = true);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function updateCategoryNode(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer = null);

    /**
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleTransfer $localeTransfer, $deleteChildren = false);

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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTree($idCategory, LocaleTransfer $localeTransfer);

    /**
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getChildren($idNode, LocaleTransfer $localeTransfer);

    /**
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleTransfer $localeTransfer, $excludeStartNode = true);

    /**
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $localeTransfer);

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

    /**
     * @api
     *
     * @param array $categoryKey
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getCategoryByKey($categoryKey, $idLocale);

}
