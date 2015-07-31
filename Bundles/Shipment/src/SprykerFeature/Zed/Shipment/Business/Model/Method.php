<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Business\Model;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Shipment\ShipmentInterface;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerFeature\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethod;
use SprykerFeature\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use SprykerFeature\Zed\Shipment\ShipmentDependencyProvider;

class Method
{

    /**
     * @var ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var array
     */
    protected $plugins;

    /**
     * @param ShipmentQueryContainerInterface $queryContainer
     */
    public function __construct(ShipmentQueryContainerInterface $queryContainer, array $plugins)
    {
        $this->queryContainer = $queryContainer;
        $this->plugins = $plugins;
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
            ->setDeliveryTimePlugin($methodTransfer->getDeliveryTimePlugin())
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
            $availabilityPlugins = $this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS];
            $isAvailable = true;

            if (array_key_exists($method->getAvailabilityPlugin(), $availabilityPlugins)) {
                /** @var ShipmentMethodAvailabilityPluginInterface $availabilityPlugin */
                $availabilityPlugin = $availabilityPlugins[$method->getAvailabilityPlugin()];
                $isAvailable = $availabilityPlugin->isAvailable($cartTransfer);
            }

            if ($isAvailable) {
                $shipmentTransfer->addMethod($methodTransfer);
            }

        }

        return $shipmentTransfer;
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function hasMethod($idMethod)
    {
        $methodQuery = $this->queryContainer->queryMethod($idMethod);

        return $methodQuery->count() > 0;
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod)
    {
        $methodQuery = $this->queryContainer->queryMethod($idMethod);
        $entity = $methodQuery->findOne();
        if (!$entity) {
            return true;
        }
        $entity->delete();

        return true;
    }

    /**
     * @param ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer)
    {
        if ($this->hasMethod($methodTransfer->getIdShipmentMethod())) {
            $methodEntity = $this->queryContainer->queryMethod($methodTransfer->getIdShipmentMethod())->findOne();
            $methodEntity
                ->setFkShipmentCarrier($methodTransfer->getFkShipmentCarrier())
                ->setGlossaryKeyName($methodTransfer->getGlossaryKeyName())
                ->setGlossaryKeyDescription($methodTransfer->getGlossaryKeyDescription())
                ->setPrice($methodTransfer->getPrice())
                ->setName($methodTransfer->getName())
                ->setIsActive($methodTransfer->getIsActive())
                ->setAvailabilityPlugin($methodTransfer->getAvailabilityPlugin())
                ->setPriceCalculationPlugin($methodTransfer->getPriceCalculationPlugin())
                ->setDeliveryTimePlugin($methodTransfer->getDeliveryTimePlugin())
                ->save()
            ;

            return $methodEntity->getPrimaryKey();
        }

        return false;
    }
}
