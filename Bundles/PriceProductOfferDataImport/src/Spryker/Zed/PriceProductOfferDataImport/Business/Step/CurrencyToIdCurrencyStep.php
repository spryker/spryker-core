<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class CurrencyToIdCurrencyStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idCurrencyCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $currencyCode = $dataSet[PriceProductOfferDataSetInterface::CURRENCY];

        if (!isset($this->idCurrencyCache[$currencyCode])) {
            $currencyQuery = SpyCurrencyQuery::create();
            $currencyQuery->select(SpyCurrencyTableMap::COL_ID_CURRENCY);
            $idCurrency = $currencyQuery->findOneByCode($currencyCode);

            if (!$idCurrency) {
                throw new EntityNotFoundException(sprintf('Could not find currency by code "%s"', $currencyCode));
            }

            $this->idCurrencyCache[$currencyCode] = $idCurrency;
        }

        $dataSet[PriceProductOfferDataSetInterface::FK_CURRENCY] = $this->idCurrencyCache[$currencyCode];
    }
}
