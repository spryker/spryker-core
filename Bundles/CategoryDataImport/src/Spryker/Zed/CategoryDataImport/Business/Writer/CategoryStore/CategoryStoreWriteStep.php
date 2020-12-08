<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\CategoryStore\Persistence\SpyCategoryStore;
use Orm\Zed\CategoryStore\Persistence\SpyCategoryStoreQuery;
use Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore\DataSet\CategoryStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class CategoryStoreWriteStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const EVENT_CATEGORY_STORE_PUBLISH = 'Category.category_store.publish';
    protected const EVENT_CATEGORY_STORE_UNPUBLISH = 'Category.category_store.unpublish';

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
        foreach ($dataSet[CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS] as $idStore) {
            if (!$this->isParentCategoryHasRelationToStore($dataSet[CategoryStoreDataSetInterface::COL_ID_CATEGORY], $idStore)) {
                continue;
            }

            $categoryStoreEntity = SpyCategoryStoreQuery::create()
                ->filterByFkCategory($dataSet[CategoryStoreDataSetInterface::COL_ID_CATEGORY])
                ->filterByFkStore($idStore)
                ->findOneOrCreate();

            if ($categoryStoreEntity->isNew()) {
                $categoryStoreEntity->save();
                $this->addPublishEvents(static::EVENT_CATEGORY_STORE_PUBLISH, $categoryStoreEntity->getIdCategoryStore());
            }
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function removeCategoryStoreRelations(DataSetInterface $dataSet): void
    {
        $categoryStoreEntities = SpyCategoryStoreQuery::create()
            ->filterByFkCategory($dataSet[CategoryStoreDataSetInterface::COL_ID_CATEGORY])
            ->filterByFkStore_In($dataSet[CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS])
            ->find();

        foreach ($categoryStoreEntities as $categoryStoreEntity) {
            $this->deleteCategoryStoreEntity($categoryStoreEntity);
        }
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
    protected function deleteChildrenCategoriesRelationToStore(int $idParentCategory, int $idStore): void
    {
        $categoryStoreEntities = $this->getChildrenCategoryEntities($idParentCategory, $idStore);

        foreach ($categoryStoreEntities as $categoryStoreEntity) {
            $this->deleteCategoryStoreEntity($categoryStoreEntity);
        }
    }

    /**
     * @param int $idParentCategory
     * @param int $idStore
     *
     * @return \Orm\Zed\CategoryStore\Persistence\SpyCategoryStore[]
     */
    protected function getChildrenCategoryEntities(int $idParentCategory, int $idStore): array
    {
        $categoryClosureTableEntities = SpyCategoryClosureTableQuery::create()
            ->filterByDepth(0, Criteria::NOT_EQUAL)
            ->useNodeQuery('parentNode', Criteria::LEFT_JOIN)
                ->filterByFkCategory($idParentCategory)
            ->endUse()
            ->leftJoinWithDescendantNode()
            ->useDescendantNodeQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithCategory()
                ->useCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinWithSpyCategoryStore()
                    ->useSpyCategoryStoreQuery(null, Criteria::LEFT_JOIN)
                        ->filterByFkStore($idStore)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->find();

        $categoryStoreEntities = [];
        foreach ($categoryClosureTableEntities as $categoryClosureTableEntity) {
            $categoryStoreEntities[] = $categoryClosureTableEntity->getDescendantNode()->getCategory()->getSpyCategoryStores()->getFirst();
        }

        return $categoryStoreEntities;
    }

    /**
     * @param \Orm\Zed\CategoryStore\Persistence\SpyCategoryStore $categoryStoreEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function deleteCategoryStoreEntity(SpyCategoryStore $categoryStoreEntity): void
    {
        $categoryStoreEntity->delete();
        $this->addPublishEvents(static::EVENT_CATEGORY_STORE_UNPUBLISH, $categoryStoreEntity->getIdCategoryStore());
    }
}
