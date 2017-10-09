<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Shared\Shipment\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ShipmentMethodDataHelper extends Module
{

    use LocatorHelperTrait;

    const NAMESPACE_ROOT = '\\';

    /**
     * First level keys represents idStore.
     * Second level key represents idCurrency.
     * Second level value represents the optional corresponding MoneyValue override values.
     */
    const DEFAULT_PRICE_LIST = [
        1 => [
            93 => [],
        ],
    ];

    /**
     * @param array $overrideShipmentMethod
     * @param array $overrideCarrier
     * @param array $priceList
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function haveShipmentMethod(array $overrideShipmentMethod = [], array $overrideCarrier = [], array $priceList = self::DEFAULT_PRICE_LIST)
    {
        $shipmentMethodTransfer = (new ShipmentMethodBuilder($overrideShipmentMethod))->build();
        $shipmentMethodTransfer = $this->assertCarrier($shipmentMethodTransfer, $overrideCarrier);

        $moneyValueTransferCollection = new ArrayObject();
        foreach ($priceList as $idStore => $currencies) {
            foreach ($currencies as $idCurrency => $moneyValueOverride) {
                $moneyValueTransferCollection->append(
                    (new MoneyValueBuilder($moneyValueOverride))
                        ->build()
                        ->setFkCurrency($idCurrency)
                        ->setFkStore($idStore)
                );
            }
        }
        $shipmentMethodTransfer->setPrices($moneyValueTransferCollection);

        $this->getShipmentFacade()->createMethod($shipmentMethodTransfer);

        return $shipmentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param array $overrideCarrier
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function assertCarrier(ShipmentMethodTransfer $shipmentMethodTransfer, array $overrideCarrier)
    {
        if ($shipmentMethodTransfer->getFkShipmentCarrier() !== null) {
            return $shipmentMethodTransfer;
        }

        $shipmentCarrierTransfer = $this->getShipmentCarrierDataHelper()->haveShipmentCarrier($overrideCarrier);
        $shipmentMethodTransfer->setFkShipmentCarrier($shipmentCarrierTransfer->getIdShipmentCarrier());

        return $shipmentMethodTransfer;
    }

    /**
     * @return \SprykerTest\Shared\Shipment\Helper\ShipmentCarrierDataHelper|\Codeception\Module
     */
    protected function getShipmentCarrierDataHelper()
    {
        return $this->getModule(static::NAMESPACE_ROOT . ShipmentCarrierDataHelper::class);
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected function getShipmentFacade()
    {
        return $this->getLocator()->shipment()->facade();
    }

}
