<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodDeliveryTimePluginInterface;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodPriceCalculationPluginInterface;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodTaxCalculationPluginInterface;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

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
     * @param array $plugins
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
        $methodEntity->fromArray($methodTransfer->toArray());
        $methodEntity->save();

        return $methodEntity->getPrimaryKey();
    }

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer)
    {
        $shipmentTransfer = new ShipmentTransfer();
        $methods = $this->queryContainer->queryActiveMethods()->find();

        foreach ($methods as $method) {
            $methodTransfer = new ShipmentMethodTransfer();
            $methodTransfer->fromArray($method->toArray());

            if ($this->isAvailable($method, $shipmentMethodAvailabilityTransfer)) {
                $methodTransfer->setPrice($this->getPrice($method, $shipmentMethodAvailabilityTransfer));
                $methodTransfer->setTaxRate($this->getTaxRate($method, $shipmentMethodAvailabilityTransfer));
                $methodTransfer->setTime($this->getDeliveryTime($method, $shipmentMethodAvailabilityTransfer));

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
        $methodQuery = $this->queryContainer->queryMethodByIdMethod($idMethod);

        return $methodQuery->count() > 0;
    }

    /**
     * @param $idMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransferById($idMethod)
    {
        $shipmentMethodTransfer = new ShipmentMethodTransfer();

        $methodQuery = $this->queryContainer->queryMethodByIdMethod($idMethod);

        $shipmentMethodTransferEntity = $methodQuery->findOne();

        $shipmentMethodTransfer->fromArray($shipmentMethodTransferEntity->toArray());

        return $shipmentMethodTransfer;
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod)
    {
        $methodQuery = $this->queryContainer->queryMethodByIdMethod($idMethod);
        $entity = $methodQuery->findOne();

        if ($entity) {
            $entity->delete();
        }

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
            $methodEntity =
                $this->queryContainer->queryMethodByIdMethod($methodTransfer->getIdShipmentMethod())->findOne();

            $methodEntity->fromArray($methodTransfer->toArray());

            $methodEntity->save();

            return $methodEntity->getPrimaryKey();
        }

        return false;
    }

    /**
     * @param SpyShipmentMethod $method
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer
     *
     * @return bool
     */
    protected function isAvailable(SpyShipmentMethod $method, ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer)
    {
        $availabilityPlugins = $this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS];
        $isAvailable = true;

        if (isset($availabilityPlugins[$method->getAvailabilityPlugin()])) {
            /** @var ShipmentMethodAvailabilityPluginInterface $availabilityPlugin */
            $availabilityPlugin = $availabilityPlugins[$method->getAvailabilityPlugin()];
            $isAvailable = $availabilityPlugin->isAvailable($shipmentMethodAvailabilityTransfer);
        }

        return $isAvailable;
    }

    /**
     * @param SpyShipmentMethod $method
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer
     *
     * @return int
     */
    protected function getPrice(SpyShipmentMethod $method, ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer)
    {
        $price = $method->getPrice();
        $priceCalculationPlugins = $this->plugins[ShipmentDependencyProvider::PRICE_CALCULATION_PLUGINS];

        if (isset($priceCalculationPlugins[$method->getPriceCalculationPlugin()])) {
            /** @var ShipmentMethodPriceCalculationPluginInterface $priceCalculationPlugin */
            $priceCalculationPlugin = $priceCalculationPlugins[$method->getPriceCalculationPlugin()];
            $price = $priceCalculationPlugin->getPrice($shipmentMethodAvailabilityTransfer);
        }

        return $price;
    }

    /**
     * @param SpyShipmentMethod $method
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer
     *
     * @return int
     */
    protected function getTaxRate(SpyShipmentMethod $method, ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer)
    {
        $taxSetEntity = $method->getTaxSet();

        $effectiveTaxRate = 0;

        if (isset($taxSetEntity)) {
            $taxRates = $taxSetEntity->getSpyTaxRates();
            foreach ($taxRates as &$taxRate) {
                $effectiveTaxRate += $taxRate->getRate();
            }
        }

        $taxCalculationPlugins = $this->plugins[ShipmentDependencyProvider::TAX_CALCULATION_PLUGINS];
        if (isset($taxCalculationPlugins[$method->getTaxCalculationPlugin()])) {
            /** @var ShipmentMethodTaxCalculationPluginInterface $taxCalculationPlugin */
            $taxCalculationPlugin = $taxCalculationPlugins[$method->getTaxCalculationPlugin()];
            $effectiveTaxRate = $taxCalculationPlugin->getTaxRate($shipmentMethodAvailabilityTransfer, $effectiveTaxRate);
        }

        return $effectiveTaxRate;
    }

    /**
     * @param SpyShipmentMethod $method
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer
     *
     * @return string
     */
    protected function getDeliveryTime(SpyShipmentMethod $method, ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer)
    {
        $timeString = '';

        $deliveryTimePlugins = $this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS];
        if (isset($deliveryTimePlugins[$method->getDeliveryTimePlugin()])) {
            /** @var ShipmentMethodDeliveryTimePluginInterface $deliveryTimePlugin */
            $deliveryTimePlugin = $deliveryTimePlugins[$method->getDeliveryTimePlugin()];
            $timeString = $deliveryTimePlugin->getTime($shipmentMethodAvailabilityTransfer);
        }

        return $timeString;
    }

}
