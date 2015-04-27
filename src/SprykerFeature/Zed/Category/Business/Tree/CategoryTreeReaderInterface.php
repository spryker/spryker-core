<?php

namespace SprykerFeature\Zed\Category\Business\Tree;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNode;

interface CategoryTreeReaderInterface
{

    /**
     * @param int $idNode
     * @param LocaleDto $locale
     * @param bool $onlyOneLevel
     * @param bool $excludeStartNode
     *
     * @return SpyCategoryNode[]
     */
    public function getChildren($idNode, LocaleDto $locale, $onlyOneLevel = true, $excludeStartNode = true);

    /**
     * @param int       $idNode
     * @param bool      $excludeRootNode
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function getParents($idNode, LocaleDto $locale, $excludeRootNode = true);

    /**
     * @param int $idNode
     * @return bool
     */
    public function hasChildren($idNode);

    /**
     * @param int       $idNode
     * @param bool      $excludeRootNode
     * @param bool      $onlyParents
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function getPath($idNode, LocaleDto $locale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @param int       $idNode
     * @param bool      $excludeRootNode
     * @param bool      $onlyParents
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function getGroupedPaths($idNode, LocaleDto $locale, $excludeRootNode = true, $onlyParents = false);

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
