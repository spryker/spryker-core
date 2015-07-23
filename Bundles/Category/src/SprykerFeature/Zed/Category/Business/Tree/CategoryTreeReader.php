<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryClosureTableTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNode;
use SprykerFeature\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException;

class CategoryTreeReader implements CategoryTreeReaderInterface
{
    /**
     * @var CategoryQueryContainer
     */
    protected $queryContainer;

    /**
     * @param CategoryQueryContainer $queryContainer
     */
    public function __construct(CategoryQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int            $idNode
     * @param LocaleTransfer $locale
     *
     * @return SpyCategoryNode[]|ObjectCollection
     */
    public function getChildren($idNode, LocaleTransfer $locale)
    {
        return $this->queryContainer
            ->queryFirstLevelChildrenByIdLocale($idNode, $locale->getIdLocale())
            ->find()
            ;
    }

    /**
     * @param int            $idNode
     * @param LocaleTransfer $locale
     * @param bool           $excludeRootNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleTransfer $locale, $excludeRootNode = true)
    {
        return $this->getGroupedPaths($idNode, $locale, $excludeRootNode, true);
    }

    /**
     * @param int            $idNode
     * @param LocaleTransfer $locale
     * @param bool           $excludeRootNode
     * @param bool           $onlyParents
     *
     * @return array
     */
    public function getPath($idNode, LocaleTransfer $locale, $excludeRootNode = true, $onlyParents = false)
    {
        return $this->queryContainer
            ->queryPath($idNode, $locale->getIdLocale(), $excludeRootNode, $onlyParents)
            ->find()
            ;
    }

    /**
     * @param int            $idNode
     * @param LocaleTransfer $locale
     * @param bool           $excludeRootNode
     * @param bool           $onlyParents
     *
     * @return array
     */
    public function getGroupedPaths($idNode, LocaleTransfer $locale, $excludeRootNode = true, $onlyParents = false)
    {
        $paths = $this->getPath($idNode, $locale, $excludeRootNode, $onlyParents);
        $groupedPaths = [];

        $field = $this->getNodeDescendantColumnName();

        foreach ($paths as $path) {
            $currentId = $path[$field];

            if (!isset($groupedPaths[$currentId])) {
                $groupedPaths[$currentId] = [];
            }
            $groupedPaths[$currentId][] = $path;
        }

        return $groupedPaths;
    }

    /**
     * @param int            $idNode
     * @param LocaleTransfer $locale
     * @param bool           $excludeRootNode
     * @param bool           $onlyParents
     *
     * @TODO Move getGroupedPathIds and getGroupedPaths to another class, duplicated Code!
     *
     * @return array
     */
    public function getGroupedPathIds($idNode, LocaleTransfer $locale, $excludeRootNode = true, $onlyParents = false)
    {
        $paths = $this->getPath($idNode, $locale, $excludeRootNode, $onlyParents);

        $groupedPathIds = [];
        $field = $this->getNodeDescendantColumnName();

        foreach ($paths as $path) {
            $idCurrent = $path[$field];

            if (!isset($groupedPathIds[$idCurrent])) {
                $groupedPathIds[$idCurrent] = [];
            }
            $groupedPathIds[$idCurrent][] = $path['id_category_node'];
        }

        return $groupedPathIds;
    }

    /**
     * @return string
     */
    protected function getNodeDescendantColumnName()
    {
        $prefixedColumnName = SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT;
        $fieldNameStartPosition = strpos($prefixedColumnName, '.') + 1;
        $columnNameLength = strlen($prefixedColumnName) - $fieldNameStartPosition;
        $columnName = substr($prefixedColumnName, $fieldNameStartPosition, $columnNameLength);

        return $columnName;
    }

    /**
     * @param int $idNode
     *
     * @return bool
     */
    public function hasChildren($idNode)
    {
        $childrenCount = $this->queryContainer
            ->queryFirstLevelChildren($idNode)
            ->count()
        ;

        return $childrenCount > 0;
    }

    /**
     * @param string         $categoryName
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleTransfer $locale)
    {
        $categoryQuery = $this->queryContainer->queryNodeByCategoryName($categoryName, $locale->getIdLocale());

        return $categoryQuery->count() > 0;
    }

    /**
     * @param string         $categoryName
     * @param LocaleTransfer $locale
     *
     * @throws MissingCategoryNodeException
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $locale)
    {
        $categoryQuery = $this->queryContainer->queryNodeByCategoryName($categoryName, $locale->getIdLocale());
        $categoryNode = $categoryQuery->findOne();

        if (!$categoryNode) {
            throw new MissingCategoryNodeException(
                sprintf(
                    'Tried to retrieve a missing category node for category %s, locale %s',
                    $categoryName,
                    $locale->getLocaleName()
                )
            );
        }

        return $categoryNode->getPrimaryKey();
    }

    /**
     * @param int $idNode
     *
     * @return SpyCategoryNode
     */
    public function getNodeById($idNode)
    {
        return $this->queryContainer
            ->queryNodeById($idNode)
            ->findOne()
            ;
    }

    /**
     * @param $idNode
     *
     * @return CategoryTransfer
     */
    public function getCategoryTransferByIdNode($idNode)
    {
        $categoryEntity = $this->getNodesByIdCategory($idNode);
        $categoryTransfer = new CategoryTransfer();
        foreach ($categoryEntity->getCategory()->getAttributes() as $attributeEntity) {
            $categoryTransfer->setName($attributeEntity->getName());
        }
        $categoryTransfer->setIdCategory($categoryEntity->getFkCategory());

        return $categoryTransfer;
    }

    /**
     * @param LocaleTransfer $localeTransfer
     *
     * @return SpyCategoryNode[]
     */
    public function getRootNodes()
    {
        return $this->queryContainer
            ->queryRootNode()
            ->find()
            ;
    }

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNode[]
     */
    public function getNodesByIdCategory($idCategory)
    {
        return $this->queryContainer
            ->queryNodesByCategoryId($idCategory)
            ->find()
            ;
    }

    /**
     * @param LocaleTransfer $localeTransfer
     *
     * @return SpyCategoryNode[]
     */
    public function getTree(LocaleTransfer $localeTransfer)
    {
        $tree = $this->getTreeNodesRecursively($localeTransfer);

        return $tree;
    }

    /**
     * @param SpyCategoryNode $node
     * @param LocaleTransfer  $localeTransfer
     *
     * @return SpyCategoryNode[]
     */
    private function getTreeNodesRecursively(LocaleTransfer $localeTransfer, SpyCategoryNode $node = null)
    {
        $tree = [];
        if ($node == null) {
            $children = $this->getRootNodes();
            $parentId = '#';
        } else {
            $children = $this->getChildren($node->getIdCategoryNode(), $localeTransfer);
            $parentId = $node->getIdCategoryNode();
        }
        foreach ($children as $child) {
            $tree[] = [
                'id' => $child->getIdCategoryNode(),
                'parent' => $parentId,
                'text' => $child->getCategory()->getAttributes()->getFirst()->getName(),
            ];
            if ($child->countDescendants() > 0) {
                $tree = array_merge($tree, $this->getTreeNodesRecursively($localeTransfer, $child));
            }
        }

        return $tree;
    }
}
