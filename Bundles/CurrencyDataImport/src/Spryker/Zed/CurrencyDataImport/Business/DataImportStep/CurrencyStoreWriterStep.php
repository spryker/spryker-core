<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CurrencyDataImport\Business\DataImportStep;

use Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\CurrencyDataImport\Business\DataSet\CurrencyStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CurrencyStoreWriterStep implements DataImportStepInterface
{
    /**
     * @var \Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery<mixed>
     */
    protected SpyCurrencyStoreQuery $currencyStoreQuery;

    /**
     * @var \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed>
     */
    protected SpyStoreQuery $storeQuery;

    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery<mixed> $currencyStoreQuery
     * @param \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed> $storeQuery
     */
    public function __construct(SpyCurrencyStoreQuery $currencyStoreQuery, SpyStoreQuery $storeQuery)
    {
        $this->currencyStoreQuery = $currencyStoreQuery;
        $this->storeQuery = $storeQuery;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<string> $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->currencyStoreQuery
            ->clear()
            ->filterByFkStore($dataSet[CurrencyStoreDataSetInterface::ID_STORE])
            ->filterByFkCurrency($dataSet[CurrencyStoreDataSetInterface::ID_CURRENCY])
            ->findOneOrCreate()
            ->save();

        $this->updateStoreDefaultCurrency($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function updateStoreDefaultCurrency(DataSetInterface $dataSet): void
    {
        if (!$dataSet[CurrencyStoreDataSetInterface::COLUMN_IS_DEFAULT]) {
            return;
        }

        $storeEntity = $this->storeQuery
            ->clear()
            ->findOneByIdStore($dataSet[CurrencyStoreDataSetInterface::ID_STORE]);

        if (!$storeEntity) {
            throw new EntityNotFoundException(sprintf('Store not found: %s', $dataSet[CurrencyStoreDataSetInterface::ID_STORE]));
        }

        $storeEntity
            ->setFkCurrency($dataSet[CurrencyStoreDataSetInterface::ID_CURRENCY])
            ->save();
    }
}
