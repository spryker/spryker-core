<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Business\Model;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Shipment\ShipmentInterface;
use Generated\Shared\Shipment\ShipmentMethodInterface;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethod;
use SprykerFeature\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class Method
{

    /**
     * @var ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param ShipmentQueryContainerInterface $queryContainer
     */
    public function __construct(ShipmentQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function create(ShipmentMethodTransfer $methodTransfer)
    {
        $methodEntity = new SpyShipmentMethod();
        $methodEntity
            ->setFkShipmentCarrier($methodTransfer->getFkShipmentCarrier())
            ->setGlossaryKeyName(
                $methodTransfer->getGlossaryKeyName()
            )
            ->setGlossaryKeyDescription(
                $methodTransfer->getGlossaryKeyDescription()
            )
            ->setPrice($methodTransfer->getPrice())
            ->setName($methodTransfer->getName())
            ->setIsActive($methodTransfer->getIsActive())
            ->setAvailabilityPlugin($methodTransfer->getAvailabilityPlugin())
            ->setPriceCalculationPlugin($methodTransfer->getPriceCalculationPlugin())
            ->setDeliveryTimePlugin($methodTransfer->getPriceCalculationPlugin())
            ->save()
        ;

        return $methodEntity->getPrimaryKey();
    }

    /**
     * @param CartInterface $cartTransfer
     *
     * @return ShipmentInterface
     */
    public function getAvailableMethods(CartInterface $cartTransfer)
    {
        $shipmentTransfer = new ShipmentTransfer();
        $methods = $this->queryContainer->queryActiveMethods()->find();

        foreach ($methods as $method) {
            $methodTransfer = new ShipmentMethodTransfer();
            $methodTransfer->fromArray($method->toArray());
            $shipmentTransfer->addMethod($methodTransfer);
        }

        return $shipmentTransfer;
    }
}
