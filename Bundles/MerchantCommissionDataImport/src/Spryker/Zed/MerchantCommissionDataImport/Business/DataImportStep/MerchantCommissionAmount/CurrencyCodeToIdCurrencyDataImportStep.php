<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataImportStep\MerchantCommissionAmount;

use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantCommissionDataImport\Business\DataSet\MerchantCommissionAmountDataSetInterface;

class CurrencyCodeToIdCurrencyDataImportStep implements DataImportStepInterface
{
    /**
     * @uses \Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap::COL_ID_CURRENCY
     *
     * @var string
     */
    protected const COL_ID_CURRENCY = 'spy_currency.id_currency';

    /**
     * @var array<string, int>
     */
    protected array $currencyIdsIndexedByCode = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $currencyCode */
        $currencyCode = $dataSet[MerchantCommissionAmountDataSetInterface::COLUMN_CURRENCY];
        if (!isset($this->currencyIdsIndexedByCode[$currencyCode])) {
            $this->currencyIdsIndexedByCode[$currencyCode] = $this->getIdCurrencyByCode($currencyCode);
        }

        $dataSet[MerchantCommissionAmountDataSetInterface::ID_CURRENCY] = $this->currencyIdsIndexedByCode[$currencyCode];
    }

    /**
     * @param string $currencyCode
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCurrencyByCode(string $currencyCode): int
    {
        /** @var int $idCurrency */
        $idCurrency = $this->getCurrencyQuery()
            ->select([static::COL_ID_CURRENCY])
            ->findOneByCode($currencyCode);

        if (!$idCurrency) {
            throw new EntityNotFoundException(
                sprintf('Could not find Currency by the code "%s"', $currencyCode),
            );
        }

        return $idCurrency;
    }

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    protected function getCurrencyQuery(): SpyCurrencyQuery
    {
        return SpyCurrencyQuery::create();
    }
}
