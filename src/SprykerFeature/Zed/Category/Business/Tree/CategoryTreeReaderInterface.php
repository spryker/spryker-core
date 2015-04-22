<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNode;

interface CategoryTreeReaderInterface
{

    /**
     * @param int $idNode
     * @param int $idLocale
     * @param bool $onlyOneLevel
     * @param bool $excludeStartNode
     *
     * @return SpyCategoryNode[]
     */
    public function getChildren($idNode, $idLocale, $onlyOneLevel = true, $excludeStartNode = true);

    /**
     * @param int       $idNode
     * @param string    $idLocale
     * @param bool      $excludeRootNode
     *
     * @return array
     */
    public function getParents($idNode, $idLocale, $excludeRootNode = true);

    /**
     * @param int $idNode
     * @return bool
     */
    public function hasChildren($idNode);

    /**
     * @param int       $idNode
     * @param string    $idLocale
     * @param bool      $excludeRootNode
     * @param bool      $onlyParents
     *
     * @return array
     */
    public function getPath($idNode, $idLocale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @param int       $idNode
     * @param string    $idLocale
     * @param bool      $excludeRootNode
     * @param bool      $onlyParents
     *
     * @return array
     */
    public function getGroupedPaths($idNode, $idLocale, $excludeRootNode = true, $onlyParents = false);

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
    public function getNodesByIdCategory($idCategory);
}
