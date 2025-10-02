<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;

class MerchantCombinedProductOfferAddPriceTypeStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $priceTypeIdsIndexedByName = [];

    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[CombinedProductOfferDataSetInterface::DATA_PRICE_TYPE_IDS_INDEXED_BY_NAME] = $this->getPriceTypeIdsIndexedByName();
    }

    /**
     * @return array<string, int>
     */
    protected function getPriceTypeIdsIndexedByName(): array
    {
        if ($this->priceTypeIdsIndexedByName) {
            return $this->priceTypeIdsIndexedByName;
        }

        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery $priceTypeQuery */
        $priceTypeQuery = SpyPriceTypeQuery::create()
            ->select([SpyPriceTypeTableMap::COL_ID_PRICE_TYPE, SpyPriceTypeTableMap::COL_NAME]);

        /** @var \Propel\Runtime\Collection\ArrayCollection<array<string, mixed>> $priceTypes */
        $priceTypes = $priceTypeQuery->find();

        $this->priceTypeIdsIndexedByName = $priceTypes->toKeyValue(
            SpyPriceTypeTableMap::COL_NAME,
            SpyPriceTypeTableMap::COL_ID_PRICE_TYPE,
        );

        return $this->priceTypeIdsIndexedByName;
    }
}
