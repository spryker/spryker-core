<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryTreeReaderInterface
{
    /**
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]
     */
    public function getChildren($idNode, LocaleTransfer $locale);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $excludeRootNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleTransfer $locale, $excludeRootNode = true);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     *
     * @return bool
     */
    public function hasChildren($idNode);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @return array
     */
    public function getPath($idNode, LocaleTransfer $locale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idParentNode
     * @param bool $excludeRoot
     *
     * @return array
     */
    public function getPathChildren($idParentNode, $excludeRoot = true);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idChildNode
     * @param int $idLocale
     * @param bool $excludeRoot
     *
     * @return array
     */
    public function getPathParents($idChildNode, $idLocale, $excludeRoot = true);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @return array
     */
    public function getGroupedPaths($idNode, LocaleTransfer $locale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @TODO Move getGroupedPathIds and getGroupedPaths to another class, duplicated Code!
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @return array
     */
    public function getGroupedPathIds($idNode, LocaleTransfer $locale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleTransfer $locale);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryKey
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getCategoryByKey($categoryKey, $idLocale);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $locale);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     *
     * @return int
     */
    public function getCategoryIdentifier($categoryName, LocaleTransfer $locale);

    /**
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode|null
     */
    public function getNodeById($idNode);

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]
     */
    public function getAllNodesByIdCategory($idCategory);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]
     */
    public function getMainNodesByIdCategory($idCategory);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]
     */
    public function getNotMainNodesByIdCategory($idCategory);

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]
     */
    public function getCategoryNodesWithOrder($idParentNode, $idLocale);

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]
     */
    public function getRootNodes();

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTree($idCategory, LocaleTransfer $localeTransfer);

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function getTreeNodeChildren($idCategory, LocaleTransfer $locale);

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $locale);

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getSubTree($idCategoryNode, LocaleTransfer $localeTransfer);
}
