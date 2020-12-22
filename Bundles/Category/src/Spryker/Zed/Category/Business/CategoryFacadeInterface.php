<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryFacadeInterface
{
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
    public function getAllNodesByIdCategory($idCategory);

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
     *  - Triggers CategoryEvents::CATEGORY_NODE_PUBLISH event for parent and children nodes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer): void;

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
     *   - Triggers CategoryEvents::CATEGORY_NODE_PUBLISH event for parent and children nodes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer): void;

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
     *  - Triggers CategoryEvents::CATEGORY_NODE_PUBLISH event for parent and children nodes
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory): void;

    /**
     * Specification:
     *  - Finds sub-trees for the category node to be deleted and moves them under the destination node
     *  - Touches deleted category-node deleted (via TouchFacade)
     *  - Triggers CategoryEvents::CATEGORY_NODE_PUBLISH event for parent and children nodes
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param int $idChildrenDestinationNode
     *
     * @return void
     */
    public function deleteNodeById($idCategoryNode, $idChildrenDestinationNode);

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
    public function updateCategoryNodeOrder($idCategoryNode, $position): void;

    /**
     * @api
     *
     * @return void
     */
    public function rebuildClosureTable();

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
    public function touchCategoryActive($idCategory);

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
     * - Checks if there is a category node on the same level with provided category by name
     *
     * @api
     *
     * @param string $name
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function checkSameLevelCategoryByNameExists(string $name, CategoryTransfer $categoryTransfer): bool;

    /**
     * Specification:
     * - Retrieves collection with all categories from DB.
     * - Forward compatibility (from next major): only categories assigned with passed $storeName will be returned.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $storeName the parameter is going to be required in the next major.
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer, ?string $storeName = null): CategoryCollectionTransfer;

    /**
     * Specification:
     * - Finds a Category transfer by id with category nodes and attributes.
     * - Returns NULL if a Category does not exist.
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryById(int $idCategory): ?CategoryTransfer;

    /**
     * Specification:
     * - Retrieve category node path.
     *
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getNodePath(int $idNode, LocaleTransfer $localeTransfer): string;

    /**
     * Specification:
     * - Retrieve url to category list.
     *
     * @api
     *
     * @return string
     */
    public function getCategoryListUrl(): string;

    /**
     * Specification:
     *  - Finds first category-node for idCategory and finds all of its children.
     *  - Formats all child category-nodes as a nested array structure.
     *  - Category-node entities sorted by node order.
     *  - If `CategoryCriteriaTransfer.withChildren`, finds one level children.
     *  - If `CategoryCriteriaTransfer.withChildrenRecursively`, finds all children recursively.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategory(CategoryCriteriaTransfer $categoryCriteriaTransfer): ?CategoryTransfer;

    /**
     * Specification:
     * - Retrieves urls entities from Persistent.
     * - Filters by category node ids when provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer $categoryNodeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getCategoryNodeUrls(CategoryNodeUrlFilterTransfer $categoryNodeFilterTransfer): array;

    /**
     * Specification:
     * - Retrieves all NodeTransfers by categoryNodeIds and all their parents and children NodeTransfers.
     *
     * @api
     *
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllCategoryNodeTreeElementsByCategoryNodeIds(array $categoryNodeIds): array;

    /**
     * Specification:
     * - Retrieves category node ids by category ids.
     *
     * @api
     *
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getCategoryNodeIdsByCategoryIds(array $categoryIds): array;

    /**
     * Specification:
     * - Retrieves category nodes by category node ids.
     *
     * @api
     *
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getCategoryNodesByCategoryNodeIds(array $categoryNodeIds): array;
}
