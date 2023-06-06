<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType;

use Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataSet\ProductOfferShipmentTypeDataSetInterface;

class ProductOfferShipmentTypeWriteDataImportStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $uuidShipmentType = $dataSet[ProductOfferShipmentTypeDataSetInterface::UUID_SHIPMENT_TYPE];
        $productOfferReference = $dataSet[ProductOfferShipmentTypeDataSetInterface::COLUMN_PRODUCT_OFFER_REFERENCE];

        $productOfferShipmentTypeEntity = $this->getProductOfferShipmentTypeQuery()
            ->filterByShipmentTypeUuid($uuidShipmentType)
            ->filterByProductOfferReference($productOfferReference)
            ->findOneOrCreate();

        $productOfferShipmentTypeEntity->save();
    }

    /**
     * @return \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery
     */
    protected function getProductOfferShipmentTypeQuery(): SpyProductOfferShipmentTypeQuery
    {
        return SpyProductOfferShipmentTypeQuery::create();
    }
}
