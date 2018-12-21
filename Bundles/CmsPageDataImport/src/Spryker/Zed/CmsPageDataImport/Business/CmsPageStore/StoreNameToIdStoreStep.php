<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport\Business\CmsPageStore;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\CmsPageDataImport\Business\DataSet\CmsPageStoreDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class StoreNameToIdStoreStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idStoreCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeName = $dataSet[CmsPageStoreDataSet::KEY_STORE_NAME];
        if (!isset($this->idStoreCache[$storeName])) {
            $storeQuery = new SpyStoreQuery();
            $storeEntity = $storeQuery
                ->findOneByName($storeName);

            if (!$storeEntity) {
                throw new EntityNotFoundException(sprintf('Could not find store by name "%s"', $storeName));
            }

            $this->idStoreCache[$storeName] = $storeEntity->getIdStore();
        }

        $dataSet[CmsPageStoreDataSet::ID_STORE] = $this->idStoreCache[$storeName];
    }
}
