<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTable;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryStore;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryPersistenceFactory getFactory()
 */
class CategoryEntityManager extends AbstractEntityManager implements CategoryEntityManagerInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function createCategory(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $categoryMapper = $this->getFactory()->createCategoryMapper();

        $categoryEntity = $categoryMapper->mapCategoryTransferToCategoryEntity($categoryTransfer, new SpyCategory());
        $categoryEntity->save();

        return $categoryMapper->mapCategory($categoryEntity, $categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function createCategoryNode(NodeTransfer $nodeTransfer): NodeTransfer
    {
        $categoryNodeMapper = $this->getFactory()->createCategoryNodeMapper();

        $categoryNodeEntity = $categoryNodeMapper->mapNodeTransferToCategoryNodeEntity($nodeTransfer, new SpyCategoryNode());
        $categoryNodeEntity->save();

        return $categoryNodeMapper->mapCategoryNode($categoryNodeEntity, $nodeTransfer);
    }

    /**
     * @param int[] $categoryIds
     * @param int[] $storeIds
     *
     * @return void
     */
    public function bulkCreateCategoryStoreRelationForStores(array $categoryIds, array $storeIds): void
    {
        $categoryIds = array_unique($categoryIds);
        $this->bulkDeleteCategoryStoreRelations($categoryIds, $storeIds);

        $propelCollection = new ObjectCollection();
        $propelCollection->setModel(SpyCategoryStore::class);

        foreach ($categoryIds as $idCategory) {
            foreach ($storeIds as $idStore) {
                $categoryStoreEntity = (new SpyCategoryStore())
                    ->setFkCategory($idCategory)
                    ->setFkStore($idStore);

                $propelCollection->append($categoryStoreEntity);
            }
        }

        $propelCollection->save();
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    public function createCategoryClosureTableRootNode(NodeTransfer $nodeTransfer): void
    {
        $idCategoryNode = $nodeTransfer->getIdCategoryNodeOrFail();

        $categoryClosureTableEntity = $this->createCategoryClosureTableEntity($idCategoryNode, $idCategoryNode);
        $categoryClosureTableEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    public function createCategoryClosureTableNodes(NodeTransfer $nodeTransfer): void
    {
        $categoryClosureTableEntities = $this->getFactory()
            ->createCategoryClosureTableQuery()
            ->filterByFkCategoryNodeDescendant($nodeTransfer->getFkParentCategoryNodeOrFail())
            ->find();

        if (!$this->getFactory()->getConfig()->isCategoryClosureTableEventsEnabled()) {
            $this->persistCategoryClosureTableNodes($nodeTransfer, $categoryClosureTableEntities);

            return;
        }

        $idCategoryNode = $nodeTransfer->getIdCategoryNodeOrFail();

        foreach ($categoryClosureTableEntities as $categoryClosureTableEntity) {
            $categoryClosureTableEntity = $this->createCategoryClosureTableEntity(
                $categoryClosureTableEntity->getFkCategoryNode(),
                $idCategoryNode,
                $categoryClosureTableEntity->getDepth() + 1
            );

            $categoryClosureTableEntity->save();
        }

        $categoryClosureTableEntity = $this->createCategoryClosureTableEntity($idCategoryNode, $idCategoryNode);
        $categoryClosureTableEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    public function createCategoryClosureTableParentEntriesForCategoryNode(NodeTransfer $nodeTransfer): void
    {
        $categoryClosureTableEntities = $this->getFactory()
            ->createCategoryClosureTableQuery()
            ->filterByFkCategoryNode($nodeTransfer->getIdCategoryNode())
            ->find();

        $parentCategoryClosureTableEntities = $this->getFactory()
            ->createCategoryClosureTableQuery()
            ->filterByFkCategoryNodeDescendant($nodeTransfer->getFkParentCategoryNode())
            ->find();

        if ($this->getFactory()->getConfig()->isCategoryClosureTableEventsEnabled()) {
            foreach ($categoryClosureTableEntities as $categoryClosureTableEntity) {
                $this->createCategoryClosureTableParentEntries($parentCategoryClosureTableEntities, $categoryClosureTableEntity);
            }

            return;
        }

        foreach ($categoryClosureTableEntities as $categoryClosureTableEntity) {
            $this->persistCategoryClosureTableParentEntries(
                $parentCategoryClosureTableEntities,
                $categoryClosureTableEntity
            );
        }

        $this->commit();
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
     *
     * @return void
     */
    public function saveCategoryAttribute(int $idCategory, CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer): void
    {
        $categoryAttributeEntity = $this->getFactory()
            ->createCategoryAttributeQuery()
            ->filterByFkCategory($idCategory)
            ->filterByFkLocale($categoryLocalizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail())
            ->findOneOrCreate();

        $categoryAttributeEntity = $this->getFactory()
            ->createCategoryLocalizedAttributeMapper()
            ->mapCategoryLocalizedAttributeTransferToCategoryAttributeEntity(
                $categoryLocalizedAttributesTransfer,
                $categoryAttributeEntity
            );

        $categoryAttributeEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $extraParentNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function saveCategoryExtraParentNode(CategoryTransfer $categoryTransfer, NodeTransfer $extraParentNodeTransfer): NodeTransfer
    {
        $categoryNodeEntity = $this->getFactory()
            ->createCategoryNodeQuery()
            ->filterByFkCategory($categoryTransfer->getIdCategory())
            ->filterByIsMain(false)
            ->filterByFkParentCategoryNode($extraParentNodeTransfer->getIdCategoryNodeOrFail())
            ->findOneOrCreate();

        $categoryNodeEntity->save();

        return $this->getFactory()
            ->createCategoryNodeMapper()
            ->mapCategoryNode($categoryNodeEntity, new NodeTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $categoryTransfer): void
    {
        $categoryEntity = $this->getFactory()->createCategoryQuery()
            ->filterByIdCategory($categoryTransfer->getIdCategoryOrFail())
            ->findOne();

        if (!$categoryEntity) {
            return;
        }

        $categoryEntity = $this->getFactory()
            ->createCategoryMapper()
            ->mapCategoryTransferToCategoryEntity($categoryTransfer, $categoryEntity);

        $categoryEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function updateCategoryNode(NodeTransfer $nodeTransfer): NodeTransfer
    {
        $categoryNodeMapper = $this->getFactory()->createCategoryNodeMapper();
        $categoryNodeEntity = $this->getFactory()
            ->createCategoryNodeQuery()
            ->findOneByIdCategoryNode($nodeTransfer->getIdCategoryNodeOrFail());

        if (!$categoryNodeEntity) {
            return $nodeTransfer;
        }

        $categoryNodeMapper->mapNodeTransferToCategoryNodeEntity($nodeTransfer, $categoryNodeEntity);
        $categoryNodeEntity->save();

        return $categoryNodeMapper->mapCategoryNode($categoryNodeEntity, $nodeTransfer);
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategory(int $idCategory): void
    {
        $this->getFactory()
            ->createCategoryQuery()
            ->findByIdCategory($idCategory)
            ->delete();
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryLocalizedAttributes(int $idCategory): void
    {
        $this->getFactory()
            ->createCategoryAttributeQuery()
            ->findByFkCategory($idCategory)
            ->delete();
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryNode(int $idCategoryNode): void
    {
        $this->getFactory()
            ->createCategoryNodeQuery()
            ->findByIdCategoryNode($idCategoryNode)
            ->delete();
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryClosureTable(int $idCategoryNode): void
    {
        $this->getFactory()
            ->createCategoryClosureTableQuery()
            ->filterByFkCategoryNode($idCategoryNode)
            ->_or()
            ->filterByFkCategoryNodeDescendant($idCategoryNode)
            ->find()
            ->delete();
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryStoreRelations(int $idCategory): void
    {
        $this->getFactory()
            ->createCategoryStoreQuery()
            ->filterByFkCategory($idCategory)
            ->find()
            ->delete();
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryClosureTableParentEntriesForCategoryNode(int $idCategoryNode): void
    {
        $categoryClosureTableQuery = $this->getFactory()
            ->createCategoryClosureTableQuery()
            ->setModelAlias('node');

        $joinCategoryNodeDescendant = new Join(
            'node.fk_category_node_descendant',
            'descendants.fk_category_node_descendant',
            Criteria::LEFT_JOIN
        );
        $joinCategoryNodeDescendant
            ->setRightTableName(SpyCategoryClosureTableTableMap::TABLE_NAME)
            ->setRightTableAlias('descendants')
            ->setLeftTableName(SpyCategoryClosureTableTableMap::TABLE_NAME)
            ->setLeftTableAlias('node');

        $joinCategoryNodeAscendant = new Join(
            'descendants.fk_category_node',
            'ascendants.fk_category_node',
            Criteria::LEFT_JOIN
        );
        $joinCategoryNodeAscendant
            ->setRightTableName(SpyCategoryClosureTableTableMap::TABLE_NAME)
            ->setRightTableAlias('ascendants')
            ->setLeftTableName(SpyCategoryClosureTableTableMap::TABLE_NAME)
            ->setLeftTableAlias('descendants');

        $categoryClosureTableQuery->addJoinObject($joinCategoryNodeDescendant)
            ->addJoinObject($joinCategoryNodeAscendant, 'ascendantsJoin')
            ->addJoinCondition(
                'ascendantsJoin',
                'ascendants.fk_category_node_descendant = node.fk_category_node'
            )
            ->where(sprintf('descendants.fk_category_node = %d', $idCategoryNode))
            ->where('ascendants.fk_category_node IS NULL')
            ->find()
            ->delete();
    }

    /**
     * @param int[] $categoryIds
     * @param int[] $storeIds
     *
     * @return void
     */
    public function bulkDeleteCategoryStoreRelationForStores(array $categoryIds, array $storeIds): void
    {
        if ($storeIds === []) {
            return;
        }

        $this->getFactory()
            ->createCategoryStoreQuery()
            ->filterByFkCategory_in($categoryIds)
            ->filterByFkStore_In($storeIds)
            ->find()
            ->delete();
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryClosureTable[]|\Propel\Runtime\Collection\ObjectCollection $parentCategoryClosureTableEntities
     * @param \Orm\Zed\Category\Persistence\SpyCategoryClosureTable $categoryClosureTableEntity
     *
     * @return void
     */
    protected function createCategoryClosureTableParentEntries(
        ObjectCollection $parentCategoryClosureTableEntities,
        SpyCategoryClosureTable $categoryClosureTableEntity
    ): void {
        foreach ($parentCategoryClosureTableEntities as $parentCategoryClosureTableEntity) {
            $depth = $categoryClosureTableEntity->getDepth() + $parentCategoryClosureTableEntity->getDepth() + 1;
            $categoryClosureTableEntity = $this->createCategoryClosureTableEntity(
                $parentCategoryClosureTableEntity->getFkCategoryNode(),
                $categoryClosureTableEntity->getFkCategoryNodeDescendant(),
                $depth
            );

            $categoryClosureTableEntity->save();
        }
    }

    /**
     * @param int $idCategoryNode
     * @param int $idCategoryNodeDescendant
     * @param int $depth
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTable
     */
    protected function createCategoryClosureTableEntity(
        int $idCategoryNode,
        int $idCategoryNodeDescendant,
        int $depth = 0
    ): SpyCategoryClosureTable {
        return (new SpyCategoryClosureTable())
            ->setFkCategoryNode($idCategoryNode)
            ->setFkCategoryNodeDescendant($idCategoryNodeDescendant)
            ->setDepth($depth);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Orm\Zed\Category\Persistence\SpyCategoryClosureTable[]|\Propel\Runtime\Collection\ObjectCollection $categoryClosureTableEntities
     *
     * @return void
     */
    protected function persistCategoryClosureTableNodes(
        NodeTransfer $nodeTransfer,
        ObjectCollection $categoryClosureTableEntities
    ): void {
        $idCategoryNode = $nodeTransfer->getIdCategoryNodeOrFail();

        foreach ($categoryClosureTableEntities as $categoryClosureTableEntity) {
            $categoryClosureTableEntity = $this->createCategoryClosureTableEntity(
                $categoryClosureTableEntity->getFkCategoryNode(),
                $idCategoryNode,
                $categoryClosureTableEntity->getDepth() + 1
            );

            $this->persist($categoryClosureTableEntity);
        }

        $categoryClosureTableEntity = $this->createCategoryClosureTableEntity($idCategoryNode, $idCategoryNode);

        $this->persist($categoryClosureTableEntity);
        $this->commit();
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryClosureTable[]|\Propel\Runtime\Collection\ObjectCollection $parentCategoryClosureTableEntities
     * @param \Orm\Zed\Category\Persistence\SpyCategoryClosureTable $categoryClosureTableEntity
     *
     * @return void
     */
    protected function persistCategoryClosureTableParentEntries(
        ObjectCollection $parentCategoryClosureTableEntities,
        SpyCategoryClosureTable $categoryClosureTableEntity
    ): void {
        foreach ($parentCategoryClosureTableEntities as $parentCategoryClosureTableEntity) {
            $depth = $categoryClosureTableEntity->getDepth() + $parentCategoryClosureTableEntity->getDepth() + 1;
            $categoryClosureTableEntity = $this->createCategoryClosureTableEntity(
                $parentCategoryClosureTableEntity->getFkCategoryNode(),
                $categoryClosureTableEntity->getFkCategoryNodeDescendant(),
                $depth
            );

            $this->persist($categoryClosureTableEntity);
        }
    }

    /**
     * @param int[] $categoryIds
     * @param int[] $storeIds
     *
     * @return void
     */
    public function bulkDeleteCategoryStoreRelations(array $categoryIds, array $storeIds): void
    {
        if ($storeIds === []) {
            return;
        }

        $this->getFactory()
            ->createCategoryStoreQuery()
            ->filterByFkCategory_in($categoryIds)
            ->filterByFkStore_In($storeIds)
            ->delete();
    }
}
