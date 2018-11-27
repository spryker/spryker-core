<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\Step;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet\PriceProductMerchantRelationshipDataSetInterface;

class IdPriceProductStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceTypeEntity = SpyPriceTypeQuery::create()
            ->filterByName($dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_TYPE])
            ->findOneOrCreate();

        if ($priceTypeEntity->isNew() || $priceTypeEntity->isModified()) {
            $priceTypeEntity->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH);
            $priceTypeEntity->save();
        }

        $priceProductQuery = SpyPriceProductQuery::create();
        $priceProductQuery->filterByFkPriceType($priceTypeEntity->getIdPriceType());

        if (!empty($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_CONCRETE])) {
            $priceProductQuery->filterByFkProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_CONCRETE]);
        } else {
            $priceProductQuery->filterByFkProductAbstract($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRODUCT_ABSTRACT]);
        }
        $productPriceEntity = $priceProductQuery->findOneOrCreate();
        $productPriceEntity->save();

        $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT] = $productPriceEntity->getIdPriceProduct();
    }
}
