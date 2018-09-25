<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryTemplateTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface CategoryFacadeInterface
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
    public function hasCategoryNode(string $categoryName, LocaleTransfer $localeTransfer): bool;

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function getNodeById(int $idNode): NodeTransfer;

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
    public function getCategoryNodeIdentifier(string $categoryName, LocaleTransfer $localeTransfer): int;

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
    public function getCategoryIdentifier(string $categoryName, LocaleTransfer $localeTransfer): int;

    /**
     * Specification:
     *  - Finds all category-node entities for idCategory
     *  - Category-node entities sorted by node order
     *  - Returns hydrated NodeTransfer collection
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllNodesByIdCategory(int $idCategory): array;

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getMainNodesByIdCategory(int $idCategory): array;

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getNotMainNodesByIdCategory(int $idCategory): array;

    /**
     * Specification:
     *  - Reads entity for idCategory from persistence
     *  - Hydrates data from entities to CategoryTransfer
     *  - Returns CategoryTransfer
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read(int $idCategory): CategoryTransfer;

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
    public function createCategory(CategoryTransfer $categoryTransfer, ?LocaleTransfer $localeTransfer = null): int;

    /**
     * Specification:
     *  - Hydrates category entity from CategoryTransfer and persists it
     *  - Hydrates category-node entity from nested NodeTransfer and persists it
     *  - Hydrates category-attribute entities from nested CategoryLocalizedAttributesTransfer (for all given locals) and persists them
     *  - Hydrates extra-parent category-node entities from nested NodeTransfer and persists them
     *  - Generates urls from category names for all given locales (names are part of the attributes)
     *  - Hydrates url entities from generated urls and persists them
     *  - Hydrates persisted entity identifiers into CategoryTransfer (and nested transfers)
     *  - Touches created category-node entities active (via TouchFacade)
     *  - Touches navigation (via TouchFacade)
     *  - Touches created url entities active (via TouchFacade)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer): void;

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
    public function updateCategory(CategoryTransfer $categoryTransfer, ?LocaleTransfer $localeTransfer = null): void;

    /**
     * Specification:
     *  - Finds category entity, hydrates it from CategoryTransfer, and persists it
     *  - Finds category-node entity, hydrates it from CategoryTransfer, and persists it
     *  - Finds category-attribute entities (for all given locals), hydrates them from CategoryTransfer, and persists them
     *  - Finds or creates extra-parent category-node entities, hydrates them from CategoryTransfer, and persists them
     *  - Generates urls from category names for all given locales (names are part of the attributes)
     *  - Finds url entities, hydrates them with generated URLs, and persists them
     *  - Touches modified category-node entities active (via TouchFacade)
     *  - Touches modified url entities active (via TouchFacade)
     *  - Touches navigation active (via TouchFacade)
     *
     *  - If parentCategoryNode changes:
     *   - Finds existing url entities for existing parent path and removes them from persistence
     *   - Re-generates urls for new path, hydrates url entities with generated urls, and persists them
     *   - Touches modified category-node entities active
     *   - Touches all category-node entities in path active
     *   - Touches modified URL entities active (via TouchFacade)
     *   - Touches removed URL entities deleted (via TouchFacade)
     *   - Touches navigation active (via TouchFacade)
     *
     *  - If existing extra-parent category-nodes entities are missing from CategoryTransfer:
     *   - Finds related extra-parent category-node entities and removes them from persistence
     *   - Finds related url entities and removes them from persistence
     *   - Finds sub-trees for all extra-parent category-nodes and moves them to the next higher node in the tree
     *   - Updates all category-node entities of all sub-trees that are moved
     *   - Touches removed category-node entities deleted (via TouchFacade)
     *   - Touches all category-node entities in path active (via TouchFacade)
     *   - Touches removed URL entities deleted (via TouchFacade)
     *   - Touches navigation active (via TouchFacade)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer): void;

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
    public function addCategoryAttribute(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer): void;

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategory(int $idCategory): void;

    /**
     * Specification:
     *  - Finds category entity and removes them from persistence
     *  - Finds category-node entity (main path) and removes it from persistence
     *  - Finds category-attribute entities and removes them from persistence
     *  - Finds extra-parent category-nodes and removes them from persistence
     *  - Finds url entities and removes them from persistence
     *  - Finds sub-trees for all category-nodes to be deleted and moves them to the next higher node in the tree
     *  - Updates all category-node entities of all sub-trees that are moved
     *  - Touches all deleted category-node entities deleted (via TouchFacade)
     *  - Touches all deleted url entities deleted (via TouchFacade)
     *  - Touches navigation active (via TouchFacade)
     *  - Calls all registered CategoryRelationDeletePluginInterface-plugins directly before removing the category entity
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function delete(int $idCategory): void;

    /**
     * Specification:
     *  - Finds sub-trees for the category node to be deleted and moves them under the destination node
     *  - Touches deleted category-node deleted (via TouchFacade)
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param int $idChildrenDestinationNode
     *
     * @return void
     */
    public function deleteNodeById(int $idCategoryNode, int $idChildrenDestinationNode): void;

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
    public function createCategoryNode(NodeTransfer $nodeTransfer, ?LocaleTransfer $localeTransfer = null, bool $createUrlPath = true): int;

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
    public function updateCategoryNode(NodeTransfer $categoryNodeTransfer, ?LocaleTransfer $localeTransfer = null): void;

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
    public function updateCategoryNodeOrder(int $idCategoryNode, int $position): void;

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
    public function deleteNode(int $idNode, LocaleTransfer $localeTransfer, bool $deleteChildren = false): int;

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @return string|false
     */
    public function renderCategoryTreeVisual();

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getRootNodes(): array;

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
    public function getTree(int $idCategory, LocaleTransfer $localeTransfer): array;

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getChildren(int $idNode, LocaleTransfer $localeTransfer): ObjectCollection;

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
    public function getParents(int $idNode, LocaleTransfer $localeTransfer, bool $excludeStartNode = true): array;

    /**
     * Specification:
     *  - Finds first category-node for idCategory and finds all of its children
     *  - Formats all child category-nodes as a nested array structure
     *  - Category-node entities sorted by node order
     *  - Returns array representation of sub-tree
     *
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale(int $idCategory, LocaleTransfer $localeTransfer): array;

    /**
     * @api
     *
     * @return void
     */
    public function rebuildClosureTable(): void;

    /**
     * Specification:
     *  - Removes circular relations from closure table
     *  - Finds all category-node entities, removes them, and re-creates them in closure table
     *
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param array $pathTokens
     *
     * @return string
     */
    public function generatePath(array $pathTokens): string;

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryKey
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getCategoryByKey(string $categoryKey, int $idLocale): CategoryTransfer;

    /**
     * Specification:
     *  - Finds all category-node entities for idCategory
     *  - Touches all nodes active
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function touchCategoryActive(int $idCategory): void;

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
    public function getSubTreeByIdCategoryNodeAndLocale(int $idCategoryNode, LocaleTransfer $localeTransfer): array;

    /**
     * Specification:
     * - Takes template list from defined config
     * - Creates new template records
     * - Does not delete/update existing template records (safe)
     *
     * @api
     *
     * @return void
     */
    public function syncCategoryTemplate(): void;

    /**
     * Specification:
     * - Finds a template by the specified name
     * - Hydrates a CategoryTemplateTransfer
     * - Returns NULL if a template does not exist
     *
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer|null
     */
    public function findCategoryTemplateByName(string $name): ?CategoryTemplateTransfer;

    /**
     * Specification:
     * - Check exist a first level children by the category name
     *
     * @api
     *
     * @param string $name
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function hasFirstLevelChildrenByName(string $name, CategoryTransfer $categoryTransfer): bool;

    /**
     * Specification:
     * - Retrieves collection with all categories from DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer): CategoryCollectionTransfer;

    /**
     * Specification:
     * - Returns all categories that are assigned to the given abstract product.
     * - The data of the returned categories are localized based on the given locale transfer.
     *
     * @api
     *
     * @param int[] $idsCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByCategoryIds(array $idsCategory, LocaleTransfer $localeTransfer): CategoryCollectionTransfer;
}
