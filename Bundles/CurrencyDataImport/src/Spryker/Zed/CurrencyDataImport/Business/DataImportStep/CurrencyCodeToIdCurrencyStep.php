<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CurrencyDataImport\Business\DataImportStep;

use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Spryker\Zed\CurrencyDataImport\Business\DataSet\CurrencyStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CurrencyCodeToIdCurrencyStep implements DataImportStepInterface
{
    /**
     * @var \Orm\Zed\Currency\Persistence\SpyCurrencyQuery<mixed>
     */
    protected $currencyQuery;

    /**
     * @var array<int>
     */
    protected static $idCurrencyCache = [];

    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrencyQuery<mixed> $currencyQuery
     */
    public function __construct(SpyCurrencyQuery $currencyQuery)
    {
        $this->currencyQuery = $currencyQuery;
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
        $currencyCode = $dataSet[CurrencyStoreDataSetInterface::COLUMN_CURRENCY_CODE];

        if (!isset(static::$idCurrencyCache[$currencyCode])) {
            $currencyEntity = $this->currencyQuery
                ->clear()
                ->filterByCode($currencyCode)
                ->findOne();

            if ($currencyEntity === null) {
                throw new EntityNotFoundException(sprintf('Currency not found: %s', $currencyCode));
            }

            static::$idCurrencyCache[$currencyCode] = $currencyEntity->getIdCurrency();
        }

        $dataSet[CurrencyStoreDataSetInterface::ID_CURRENCY] = static::$idCurrencyCache[$currencyCode];
    }
}
