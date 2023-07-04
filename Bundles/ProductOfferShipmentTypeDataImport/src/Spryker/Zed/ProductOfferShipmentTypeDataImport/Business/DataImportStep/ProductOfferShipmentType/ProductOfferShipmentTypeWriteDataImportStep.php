<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType;

use Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataSet\ProductOfferShipmentTypeDataSetInterface;

class ProductOfferShipmentTypeWriteDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig::PRODUCT_OFFER_SHIPMENT_TYPE_PUBLISH
     *
     * @var string
     */
    protected const PRODUCT_OFFER_SHIPMENT_TYPE_PUBLISH = 'ProductOfferShipmentType.product_offer_shipment_type.publish';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idShipmentType = $dataSet[ProductOfferShipmentTypeDataSetInterface::ID_SHIPMENT_TYPE];
        $idProductOffer = $dataSet[ProductOfferShipmentTypeDataSetInterface::ID_PRODUCT_OFFER];

        $productOfferShipmentTypeEntity = $this->getProductOfferShipmentTypeQuery()
            ->filterByFkProductOffer($idProductOffer)
            ->filterByFkShipmentType($idShipmentType)
            ->findOneOrCreate();

        if (!$productOfferShipmentTypeEntity->isNew() && !$productOfferShipmentTypeEntity->isModified()) {
            return;
        }

        $productOfferShipmentTypeEntity->save();

        $this->addPublishEvents(
            static::PRODUCT_OFFER_SHIPMENT_TYPE_PUBLISH,
            $productOfferShipmentTypeEntity->getIdProductOfferShipmentType(),
        );
    }

    /**
     * @return \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery
     */
    protected function getProductOfferShipmentTypeQuery(): SpyProductOfferShipmentTypeQuery
    {
        return SpyProductOfferShipmentTypeQuery::create();
    }
}
