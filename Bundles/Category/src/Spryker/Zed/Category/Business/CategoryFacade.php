<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Category\Business\CategoryBusinessFactory getFactory()
 */
class CategoryFacade extends AbstractFacade implements CategoryFacadeInterface
{

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->hasCategoryNode($categoryName, $localeTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function getNodeById($idNode)
    {
        $nodeEntity = $this->getFactory()
            ->createCategoryTreeReader()
            ->getNodeById($idNode);

        return $this->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNode($nodeEntity);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryNodeIdentifier($categoryName, $localeTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function getCategoryIdentifier($categoryName, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryIdentifier($categoryName, $localeTransfer);
    }

    /**
     * Specification:
     *  - Finds all category-node entities for idCategory
     *  - Returns hydrated NodeTransfer collection
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getFactory()
            ->createCategoryTreeReader()
            ->getAllNodesByIdCategory($idCategory);

        return $this->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getMainNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getFactory()
            ->createCategoryTreeReader()
            ->getMainNodesByIdCategory($idCategory);

        return $this->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getNotMainNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getFactory()
            ->createCategoryTreeReader()
            ->getNotMainNodesByIdCategory($idCategory);

        return $this->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * Specification:
     *  - Reads entity for idCategory from persistence
     *  - Hydrates data from entities to CategoryTransfer
     *  - Throws MissingCategoryException if category entity is not present
     *  - Throws MissingCategoryNodeException if related category-node entity is not present
     *  - Returns CategoryTransfer
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory)
    {
        return $this
            ->getFactory()
            ->createCategory()
            ->read($idCategory);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return int
     */
    public function createCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createCategoryWriter()
            ->create($categoryTransfer, $localeTransfer);
    }

    /**
     * Specification:
     *  - Hydrates category entity from CategoryTransfer and persists it
     *  - Hydrates category-node entity from CategoryTransfer and persists it
     *  - Hydrates category-attribute entities from CategoryTransfer (for all given locals) and persists them
     *  - Hydrates extra-parent category-node entities from CategoryTransfer and persists them
     *  - Generates urls from category names for all given locales (names are part of the attributes)
     *  - Hydrates url entities from generated urls and persists them
     *  - Throws CategoryUrlExistsException if generated url already exists
     *  - Hydrates persisted entity identifiers into CategoryTransfer
     *  - Touches created category-node entities active
     *  - Touches navigation
     *  - Touches created url entities active
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $this->getFactory()->createCategory()->create($categoryTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer = null)
    {
        $this->getFactory()
            ->createCategoryWriter()
            ->update($categoryTransfer, $localeTransfer);
    }

    /**
     * Specification:
     *  - Finds category entity, hydrates it from CategoryTransfer, and persists it
     *  - Throws MissingCategoryException if category entity is not present
     *  - Finds category-node entity, hydrates it from CategoryTransfer, and persists it
     *  - Throws MissingCategoryNodeException if category-node entity is not present
     *  - Finds category-attribute entities (for all given locals), hydrates them from CategoryTransfer, and persists them
     *  - Finds or creates extra-parent category-node entities, hydrates them from CategoryTransfer, and persists them
     *  - Generates urls from category names for all given locales (names are part of the attributes)
     *  - Finds url entities, hydrates them with generated URLs, and persists them
     *  - Throws CategoryUrlExistsException if generated URL already exists
     *  - Touches modified category-node entities active
     *  - Touches modified url entities active
     *  - Touches navigation active
     *
     *  - If parentCategoryNode changes:
     *   - Finds existing url entities for existing parent path and removes them from persistence
     *   - Re-generates urls for new path, hydrates url entities with generated urls, and persists them
     *   - Touches modified category-node entities active
     *   - Touches all category-node entities in path active
     *   - Touches modified URL entities active
     *   - Touches removed URL entities deleted
     *   - Touches navigation active
     *
     *  - If existing extra-parent category-nodes entities are missing from CategoryTransfer:
     *   - Finds related extra-parent category-node entities and removes them from persistence
     *   - Finds related url entities and removes them from persistence
     *   - Finds sub-trees for all extra-parent category-nodes and moves them to the next higher node in the tree
     *   - Updates all category-node entities of all sub-trees that are moved
     *   - Touches removed category-node entities deleted
     *   - Touches all category-node entities in path active
     *   - Touches removed URL entities deleted
     *   - Touches navigation active
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $this->getFactory()->createCategory()->update($categoryTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function addCategoryAttribute(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer)
    {
        $this->getFactory()
            ->createCategoryWriter()
            ->addCategoryAttribute($categoryTransfer, $localeTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategory($idCategory)
    {
        $this->getFactory()
            ->createCategoryWriter()
            ->delete($idCategory);
    }

    /**
     * Specification:
     *  - Finds category entity and removes them from persistence
     *  - Throws MissingCategoryException if entity does not exist
     *  - Finds category-node entity (main path) and removes it from persistence
     *  - Finds category-attribute entities and removes them from persistence
     *  - Finds extra-parent category-nodes and removes them from persistence
     *  - Finds url entities and removes them from persistence
     *  - Finds sub-trees for all category-nodes to be deleted and moves them to the next higher node in the tree
     *  - Updates all category-node entities of all sub-trees that are moved
     *  - Touches all deleted category-node entities deleted
     *  - Touches all deleted url entities deleted
     *  - Touches navigation active
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $this->getFactory()->createCategory()->delete($idCategory);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode(NodeTransfer $nodeTransfer, LocaleTransfer $localeTransfer = null, $createUrlPath = true)
    {
        return $this->getFactory()
            ->createCategoryTreeWriter()
            ->createCategoryNode($nodeTransfer, $localeTransfer, $createUrlPath);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function updateCategoryNode(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer = null)
    {
        $this->getFactory()
            ->createCategoryTreeWriter()
            ->updateNode($categoryNodeTransfer, $localeTransfer);
    }

    /**
     * Specification:
     *  - Finds category-node entity, updates node_order field, and persists it
     *  - Touches category-node entity active
     *  - Touches navigation active
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param int $position
     *
     * @return void
     */
    public function updateCategoryNodeOrder($idCategoryNode, $position)
    {
        $this
            ->getFactory()
            ->createNodeWriter()
            ->updateOrder($idCategoryNode, $position);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleTransfer $localeTransfer, $deleteChildren = false)
    {
        return $this->getFactory()
            ->createCategoryTreeWriter()
            ->deleteNode($idNode, $localeTransfer, $deleteChildren);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @return bool
     */
    public function renderCategoryTreeVisual()
    {
        return $this->getFactory()
            ->createCategoryTreeRenderer()
            ->render();
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getRootNodes()
    {
        $rootNodes = $this->getFactory()
            ->createCategoryTreeReader()
            ->getRootNodes();

        return $this->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($rootNodes);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTree($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getTree($idCategory, $localeTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getChildren($idNode, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getChildren($idNode, $localeTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleTransfer $localeTransfer, $excludeStartNode = true)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getParents($idNode, $localeTransfer, $excludeStartNode);
    }

    /**
     * Specification:
     *  - Finds first category-node for idCategory and finds all of its children
     *  - Formats all child category-nodes as a nested array structure
     *  - Returns array representation of sub-tree
     *
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getTreeNodeChildrenByIdCategoryAndLocale($idCategory, $localeTransfer);
    }

    /**
     * Specification:
     *  - Finds all category-nodes that are children of idCategoryNode
     *  - Formats all child category-nodes as a nested array structure
     *  - Return array representation of ub-tree
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getSubTreeByIdCategoryNodeAndLocale($idCategoryNode, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getSubTree($idCategoryNode, $localeTransfer);
    }

    /**
     * Specification:
     *  - Removes circular relations from closure table
     *  - Finds all category-node entities, removes them, and re-creates them in closure table
     *
     * @api
     *
     * @return void
     */
    public function rebuildClosureTable()
    {
        $this
            ->getFactory()
            ->createClosureTableWriter()
            ->rebuildCategoryNodes();
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param array $pathTokens
     *
     * @return string
     */
    public function generatePath(array $pathTokens)
    {
        return $this->getFactory()
            ->createUrlPathGenerator()
            ->generate($pathTokens);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param array $categoryKey
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getCategoryByKey($categoryKey, $idLocale)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryByKey($categoryKey, $idLocale);
    }

}
