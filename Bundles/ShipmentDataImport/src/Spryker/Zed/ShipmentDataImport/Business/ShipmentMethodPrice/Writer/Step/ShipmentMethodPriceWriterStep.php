<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodPrice\Writer\Step;

use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodPrice\Writer\DataSet\ShipmentMethodPriceDataSetInterface;

class ShipmentMethodPriceWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shipmentMethodPriceEntity = SpyShipmentMethodPriceQuery::create()
            ->filterByFkShipmentMethod($dataSet[ShipmentMethodPriceDataSetInterface::COL_ID_SHIPMENT_METHOD])
            ->filterByFkCurrency($dataSet[ShipmentMethodPriceDataSetInterface::COL_ID_CURRENCY])
            ->filterByFkStore($dataSet[ShipmentMethodPriceDataSetInterface::COL_ID_STORE])
            ->findOneOrCreate();

        $shipmentMethodPriceEntity->setDefaultNetPrice($dataSet[ShipmentMethodPriceDataSetInterface::COL_NET_AMOUNT]);
        $shipmentMethodPriceEntity->setDefaultGrossPrice($dataSet[ShipmentMethodPriceDataSetInterface::COL_GROSS_AMOUNT]);
        $shipmentMethodPriceEntity->save();
    }
}
