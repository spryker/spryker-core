<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class PriceTypeToIdPriceTypeStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idPriceTypeCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceTypeName = $dataSet[PriceProductOfferDataSetInterface::PRICE_TYPE];

        if (!isset($this->idPriceTypeCache[$priceTypeName])) {
            $priceTypeEntity = SpyPriceTypeQuery::create()
                ->filterByName($priceTypeName)
                ->findOneOrCreate();

            if ($priceTypeEntity->isNew() || $priceTypeEntity->isModified()) {
                $priceTypeEntity->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH);
                $priceTypeEntity->save();
            }

            $this->idPriceTypeCache[$priceTypeName] = $priceTypeEntity->getIdPriceType();
        }

        $dataSet[PriceProductOfferDataSetInterface::FK_PRICE_TYPE] = $this->idPriceTypeCache[$priceTypeName];
    }
}
