<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodStore\Writer\Step;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodStore\Writer\DataSet\ShipmentMethodStoreDataSetInterface;

class StoreNameToIdStoreStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idStoreCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeName = $dataSet[ShipmentMethodStoreDataSetInterface::COL_STORE_NAME];

        if (!$storeName) {
            throw new DataKeyNotFoundInDataSetException(sprintf('Store name is missing'));
        }

        if (!isset(static::$idStoreCache[$storeName])) {
            $storeEntity = SpyStoreQuery::create()
                ->filterByName($storeName)
                ->findOne();

            if ($storeEntity === null) {
                throw new EntityNotFoundException(sprintf('Store not found: %s', $storeName));
            }

            static::$idStoreCache[$storeName] = $storeEntity->getIdStore();
        }

        $dataSet[ShipmentMethodStoreDataSetInterface::COL_ID_STORE] = static::$idStoreCache[$storeName];
    }
}
