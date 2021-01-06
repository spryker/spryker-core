<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryStore;
use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;
use Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore\DataSet\CategoryStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class CategoryStoreWriteStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\CategoryStorage\CategoryStorageConstants::CATEGORY_STORE_PUBLISH
     */
    protected const EVENT_CATEGORY_STORE_PUBLISH = 'Category.category_store.publish';

    /**
     * @uses \Spryker\Shared\CategoryStorage\CategoryStorageConstants::CATEGORY_STORE_UNPUBLISH
     */
    protected const EVENT_CATEGORY_STORE_UNPUBLISH = 'Category.category_store.unpublish';

    protected const CATEGORY_CLOSURE_TABLE_SELF_CATEGORY_NODE_DEPTH = 0;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->addCategoryStoreRelations($dataSet);
        $this->removeCategoryStoreRelations($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function addCategoryStoreRelations(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[CategoryStoreDataSetInterface::INCLUDED_STORE_IDS] as $idStore) {
            if (!$this->isParentCategoryHasRelationToStore($dataSet[CategoryStoreDataSetInterface::ID_CATEGORY], $idStore)) {
                continue;
            }

            $this->createCategoryStoreEntity($dataSet[CategoryStoreDataSetInterface::ID_CATEGORY], $idStore);
            $this->addChildrenCategoriesRelationToStore($dataSet[CategoryStoreDataSetInterface::ID_CATEGORY], $idStore);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function removeCategoryStoreRelations(DataSetInterface $dataSet): void
    {
        if ($dataSet[CategoryStoreDataSetInterface::EXCLUDED_STORE_IDS] === []) {
            return;
        }

        $categoryStoreEntities = SpyCategoryStoreQuery::create()
            ->filterByFkCategory($dataSet[CategoryStoreDataSetInterface::ID_CATEGORY])
            ->filterByFkStore_In($dataSet[CategoryStoreDataSetInterface::EXCLUDED_STORE_IDS])
            ->find();

        foreach ($categoryStoreEntities as $categoryStoreEntity) {
            $this->deleteCategoryStoreEntity($categoryStoreEntity);
        }
        $this->deleteChildrenCategoriesRelationToStore(
            $dataSet[CategoryStoreDataSetInterface::ID_CATEGORY],
            $dataSet[CategoryStoreDataSetInterface::EXCLUDED_STORE_IDS]
        );
    }

    /**
     * @param int $idCategory
     * @param int $idStore
     *
     * @return bool
     */
    protected function isParentCategoryHasRelationToStore(int $idCategory, int $idStore): bool
    {
        return SpyCategoryNodeQuery::create()
            ->filterByFkCategory($idCategory)
            ->useParentCategoryNodeQuery('parentCategory', Criteria::LEFT_JOIN)
                ->useCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyCategoryStoreQuery(null, Criteria::LEFT_JOIN)
                        ->filterByFkStore($idStore)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->_or()
            ->where(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE . ' IS NULL')
            ->exists();
    }

    /**
     * @param int $idParentCategory
     * @param int $idStore
     *
     * @return void
     */
    protected function addChildrenCategoriesRelationToStore(int $idParentCategory, int $idStore): void
    {
        $categoryEntities = $this->getChildrenCategoryEntities($idParentCategory);

        foreach ($categoryEntities as $categoryEntity) {
            $this->createCategoryStoreEntity($categoryEntity->getIdCategory(), $idStore);
        }
    }

    /**
     * @param int $idParentCategory
     * @param int[] $storeIds
     *
     * @return void
     */
    protected function deleteChildrenCategoriesRelationToStore(int $idParentCategory, array $storeIds): void
    {
        $categoryStoreEntities = $this->getChildrenCategoryStoreEntities($idParentCategory, $storeIds);

        foreach ($categoryStoreEntities as $categoryStoreEntity) {
            $this->deleteCategoryStoreEntity($categoryStoreEntity);
        }
    }

    /**
     * @param int $idParentCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory[]
     */
    protected function getChildrenCategoryEntities(int $idParentCategory): array
    {
        $categoryClosureTableEntities = SpyCategoryClosureTableQuery::create()
            ->filterByDepth(static::CATEGORY_CLOSURE_TABLE_SELF_CATEGORY_NODE_DEPTH, Criteria::NOT_EQUAL)
            ->useNodeQuery('parentNode', Criteria::LEFT_JOIN)
                ->filterByFkCategory($idParentCategory)
            ->endUse()
            ->leftJoinWithDescendantNode()
            ->useDescendantNodeQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithCategory()
            ->endUse()
            ->find();

        $categoryEntities = [];
        foreach ($categoryClosureTableEntities as $categoryClosureTableEntity) {
            $categoryEntities[] = $categoryClosureTableEntity->getDescendantNode()->getCategory();
        }

        return $categoryEntities;
    }

    /**
     * @param int $idParentCategory
     * @param int[] $storeIds
     *
     * @return \Orm\Zed\CategoryStore\Persistence\SpyCategoryStore[]
     */
    protected function getChildrenCategoryStoreEntities(int $idParentCategory, array $storeIds): array
    {
        $categoryClosureTableEntities = SpyCategoryClosureTableQuery::create()
            ->filterByDepth(static::CATEGORY_CLOSURE_TABLE_SELF_CATEGORY_NODE_DEPTH, Criteria::NOT_EQUAL)
            ->useNodeQuery('parentNode', Criteria::LEFT_JOIN)
                ->filterByFkCategory($idParentCategory)
            ->endUse()
            ->leftJoinWithDescendantNode()
            ->useDescendantNodeQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithCategory()
                ->useCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinWithSpyCategoryStore()
                    ->useSpyCategoryStoreQuery(null, Criteria::LEFT_JOIN)
                        ->filterByFkStore_In($storeIds)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->find();

        if ($categoryClosureTableEntities->count() === 0) {
            return [];
        }

        $categoryStoreEntities = [];
        foreach ($categoryClosureTableEntities as $categoryClosureTableEntity) {
            $categoryStoreEntities[] = $categoryClosureTableEntity->getDescendantNode()->getCategory()->getSpyCategoryStores()->getArrayCopy();
        }

        return array_merge(...$categoryStoreEntities);
    }

    /**
     * @param int $idCategory
     * @param int $idStore
     *
     * @return void
     */
    protected function createCategoryStoreEntity(int $idCategory, int $idStore): void
    {
        $categoryStoreEntity = SpyCategoryStoreQuery::create()
            ->filterByFkCategory($idCategory)
            ->filterByFkStore($idStore)
            ->findOneOrCreate();

        if ($categoryStoreEntity->isNew()) {
            $categoryStoreEntity->save();
            $this->addPublishEvents(static::EVENT_CATEGORY_STORE_PUBLISH, $categoryStoreEntity->getFkCategory());
        }
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryStore $categoryStoreEntity
     *
     * @return void
     */
    protected function deleteCategoryStoreEntity(SpyCategoryStore $categoryStoreEntity): void
    {
        $categoryStoreEntity->delete();
        $this->addPublishEvents(static::EVENT_CATEGORY_STORE_UNPUBLISH, $categoryStoreEntity->getFkCategory());
    }
}
