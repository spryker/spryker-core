<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore;

use Orm\Zed\CategoryStore\Persistence\SpyCategoryStoreQuery;
use Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore\DataSet\CategoryStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

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
            $categoryStoreEntity->delete();
            $this->addPublishEvents(static::EVENT_CATEGORY_STORE_UNPUBLISH, $categoryStoreEntity->getIdCategoryStore());
        }
    }
}
