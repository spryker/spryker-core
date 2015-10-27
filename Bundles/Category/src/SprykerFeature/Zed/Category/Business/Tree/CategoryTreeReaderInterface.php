<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Locale\LocaleInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNode;
use SprykerFeature\Zed\ProductCategory\Business\Exception\MissingCategoryException;
use SprykerFeature\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException;

interface CategoryTreeReaderInterface
{

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     *
     * @return SpyCategoryNode[]
     */
    public function getChildren($idNode, LocaleTransfer $locale);

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     * @param bool $excludeRootNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleTransfer $locale, $excludeRootNode = true);

    /**
     * @param int $idNode
     *
     * @return bool
     */
    public function hasChildren($idNode);

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @return array
     */
    public function getPath($idNode, LocaleTransfer $locale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @param int $idParentNode
     * @param bool $excludeRoot
     *
     * @return array
     */
    public function getPathChildren($idParentNode, $excludeRoot = true);

    /**
     * @param int $idChildNode
     * @param bool $excludeRoot
     *
     * @return array
     */
    public function getPathParents($idChildNode, $excludeRoot = true);

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @return array
     */
    public function getGroupedPaths($idNode, LocaleTransfer $locale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @TODO Move getGroupedPathIds and getGroupedPaths to another class, duplicated Code!
     *
     * @return array
     */
    public function getGroupedPathIds($idNode, LocaleTransfer $locale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleTransfer $locale);

    /**
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @throws MissingCategoryNodeException
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $locale);

    /**
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @throws MissingCategoryException
     *
     * @return int
     */
    public function getCategoryIdentifier($categoryName, LocaleTransfer $locale);

    /**
     * @param int $idNode
     *
     * @return SpyCategoryNode
     */
    public function getNodeById($idNode);

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNode[]
     */
    public function getAllNodesByIdCategory($idCategory);

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNode[]
     */
    public function getMainNodesByIdCategory($idCategory);

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNode[]
     */
    public function getNotMainNodesByIdCategory($idCategory);

    /**
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return SpyCategoryNode[]
     */
    public function getCategoryNodesWithOrder($idParentNode, $idLocale);

    /**
     * @return SpyCategoryNode[]
     */
    public function getRootNodes();

    /**
     * @param int $idCategory
     * @param LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTree($idCategory, LocaleTransfer $localeTransfer);

    /**
     * @param int $idCategory
     * @param LocaleInterface $locale
     *
     * @return array
     */
    public function getTreeNodeChildren($idCategory, LocaleInterface $locale);

    /**
     * @param int $idCategory
     * @param LocaleInterface $locale
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleInterface $locale);

}
