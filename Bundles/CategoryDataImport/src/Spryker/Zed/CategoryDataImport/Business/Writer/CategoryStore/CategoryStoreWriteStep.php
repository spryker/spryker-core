<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;
use Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore\DataSet\CategoryStoreDataSetInterface;
use Spryker\Zed\CategoryDataImport\Dependency\Facade\CategoryDataImportToCategoryFacadeInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CategoryStoreWriteStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\CategoryStorage\CategoryStorageConstants::CATEGORY_STORE_PUBLISH
     */
    protected const EVENT_CATEGORY_STORE_PUBLISH = 'Category.category_store.publish';

    /**
     * @var \Spryker\Zed\CategoryDataImport\Dependency\Facade\CategoryDataImportToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\CategoryDataImport\Dependency\Facade\CategoryDataImportToCategoryFacadeInterface $categoryFacade
     */
    public function __construct(CategoryDataImportToCategoryFacadeInterface $categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $existingStoreRelationTransfer = $this->getExistingCategoryStoreRelations($dataSet[CategoryStoreDataSetInterface::ID_CATEGORY]);

        $storeIdsToAdd = $dataSet[CategoryStoreDataSetInterface::INCLUDED_STORE_IDS];
        $storeIdsToDelete = $dataSet[CategoryStoreDataSetInterface::EXCLUDED_STORE_IDS];

        $newStoreRelationTransfer = $this->createStoreRelationTransferToAssign($storeIdsToAdd, $storeIdsToDelete, $existingStoreRelationTransfer);

        $this->categoryFacade->updateCategoryStoreRelationWithMainChildrenPropagation(
            $dataSet[CategoryStoreDataSetInterface::ID_CATEGORY],
            $newStoreRelationTransfer,
            $existingStoreRelationTransfer
        );

        $this->addPublishEvents(static::EVENT_CATEGORY_STORE_PUBLISH, $dataSet[CategoryStoreDataSetInterface::ID_CATEGORY]);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function getExistingCategoryStoreRelations(int $idCategory): StoreRelationTransfer
    {
        $storeIds = SpyCategoryStoreQuery::create()
            ->filterByFkCategory($idCategory)
            ->select(SpyCategoryStoreTableMap::COL_FK_STORE)
            ->find()
            ->getData();

        return (new StoreRelationTransfer())->setIdStores($storeIds);
    }

    /**
     * @param int[] $storeIdsToAdd
     * @param int[] $storeIdsToDelete
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $existingStoreRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function createStoreRelationTransferToAssign(
        array $storeIdsToAdd,
        array $storeIdsToDelete,
        StoreRelationTransfer $existingStoreRelationTransfer
    ): StoreRelationTransfer {
        $newStoreRelationTransfer = (new StoreRelationTransfer())->setIdStores($storeIdsToAdd);

        foreach ($existingStoreRelationTransfer->getIdStores() as $idStore) {
            if (in_array($idStore, $storeIdsToDelete) || in_array($idStore, $newStoreRelationTransfer->getIdStores())) {
                continue;
            }
            $newStoreRelationTransfer->addIdStores($idStore);
        }

        return $newStoreRelationTransfer;
    }
}
