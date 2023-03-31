<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CurrencyDataImport\Business\DataImportStep;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\CurrencyDataImport\Business\DataSet\CurrencyStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class StoreNameToIdStoreStep implements DataImportStepInterface
{
    /**
     * @var \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed>
     */
    protected $storeQuery;

    /**
     * @var array<int>
     */
    protected static $idStoreCache = [];

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed> $storeQuery
     */
    public function __construct(SpyStoreQuery $storeQuery)
    {
        $this->storeQuery = $storeQuery;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<string> $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeName = $dataSet[CurrencyStoreDataSetInterface::COLUMN_STORE_NAME];

        if (!isset(static::$idStoreCache[$storeName])) {
            $storeEntity = $this->storeQuery
                ->clear()
                ->filterByName($storeName)
                ->findOne();

            if ($storeEntity === null) {
                throw new EntityNotFoundException(sprintf('Store not found: %s', $storeName));
            }

            static::$idStoreCache[$storeName] = $storeEntity->getIdStore();
        }

        $dataSet[CurrencyStoreDataSetInterface::ID_STORE] = static::$idStoreCache[$storeName];
    }
}
