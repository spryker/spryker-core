<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;

class MerchantCombinedProductOfferAddCurrenciesStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $currencyIdsIndexedByCode = [];

    /**
     * @var array<string, array<string>>
     */
    protected array $currencyNamesIndexedByStoreName = [];

    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[CombinedProductOfferDataSetInterface::DATA_CURRENCY_IDS_INDEXED_BY_CODE] = $this->getCurrencyIdsIndexedByCode();
        $dataSet[CombinedProductOfferDataSetInterface::DATA_CURRENCY_NAMES_INDEXED_BY_STORE_NAME] = $this->getCurrencyNamesIndexedByStoreName();
    }

    /**
     * @return array<string, int>
     */
    protected function getCurrencyIdsIndexedByCode(): array
    {
        if (!$this->currencyIdsIndexedByCode) {
            /** @var \Orm\Zed\Currency\Persistence\SpyCurrencyQuery $currencyQuery */
            $currencyQuery = SpyCurrencyQuery::create()
                ->select([SpyCurrencyTableMap::COL_ID_CURRENCY, SpyCurrencyTableMap::COL_CODE]);

            /** @var \Propel\Runtime\Collection\ArrayCollection $currencies */
            $currencies = $currencyQuery->find();

            $this->currencyIdsIndexedByCode = $currencies->toKeyValue(
                SpyCurrencyTableMap::COL_CODE,
                SpyCurrencyTableMap::COL_ID_CURRENCY,
            );
        }

        return $this->currencyIdsIndexedByCode;
    }

    /**
     * @return array<string, array<string>>
     */
    protected function getCurrencyNamesIndexedByStoreName(): array
    {
        if ($this->currencyNamesIndexedByStoreName) {
            return $this->currencyNamesIndexedByStoreName;
        }

        /** @var \Orm\Zed\Currency\Persistence\SpyCurrencyQuery $currencyQuery */
        $currencyQuery = SpyCurrencyQuery::create()
            ->useCurrencyStoreQuery()
                ->joinWithStore()
            ->endUse()
            ->select([SpyCurrencyTableMap::COL_CODE, SpyStoreTableMap::COL_NAME]);

        /** @var \Propel\Runtime\Collection\ArrayCollection<array<string, mixed>> $storeCurrencies */
        $storeCurrencies = $currencyQuery->find();

        foreach ($storeCurrencies as $storeCurrency) {
            /** @var string $storeName */
            $storeName = $storeCurrency[SpyStoreTableMap::COL_NAME];

            /** @var string $currencyCode */
            $currencyCode = $storeCurrency[SpyCurrencyTableMap::COL_CODE];

            $this->currencyNamesIndexedByStoreName[$storeName][] = $currencyCode;
        }

        return $this->currencyNamesIndexedByStoreName;
    }
}
