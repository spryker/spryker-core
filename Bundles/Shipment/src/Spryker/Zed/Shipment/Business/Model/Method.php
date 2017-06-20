<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

class Method
{

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var array
     */
    protected $plugins;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     * @param array $plugins
     */
    public function __construct(ShipmentQueryContainerInterface $queryContainer, array $plugins)
    {
        $this->queryContainer = $queryContainer;
        $this->plugins = $plugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        $shipmentMethodsTransfer = new ShipmentMethodsTransfer();
        $methods = $this->queryContainer->queryActiveMethods()->find();

        foreach ($methods as $shipmentMethodEntity) {
            $shipmentMethodTransfer = new ShipmentMethodTransfer();
            $shipmentMethodTransfer->setTaxRate($this->getEffectiveTaxRate($shipmentMethodEntity));
            $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray());

            if ($this->isAvailable($shipmentMethodEntity, $quoteTransfer)) {
                $shipmentMethodTransfer->setDefaultPrice($this->getPrice($shipmentMethodEntity, $quoteTransfer));
                $shipmentMethodTransfer->setDeliveryTime($this->getDeliveryTime($shipmentMethodEntity, $quoteTransfer));
                $shipmentMethodTransfer->setCarrierName($shipmentMethodEntity->getShipmentCarrier());

                $shipmentCarrierName = $this->findShipmentCarrierName($shipmentMethodEntity);
                $shipmentMethodTransfer->setCarrierName($shipmentCarrierName);

                $shipmentMethodsTransfer->addMethod($shipmentMethodTransfer);
            }
        }

        return $shipmentMethodsTransfer;
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
     * @param int $idMethod
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
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
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
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAvailable(SpyShipmentMethod $method, QuoteTransfer $quoteTransfer)
    {
        $availabilityPlugins = $this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS];
        $isAvailable = true;

        if (isset($availabilityPlugins[$method->getAvailabilityPlugin()])) {
            $availabilityPlugin = $this->getAvailabilityPlugin($method, $availabilityPlugins);
            $isAvailable = $availabilityPlugin->isAvailable($quoteTransfer);
        }

        return $isAvailable;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param array $availabilityPlugins
     *
     * @return \Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface
     */
    protected function getAvailabilityPlugin(SpyShipmentMethod $method, array $availabilityPlugins)
    {
        return $availabilityPlugins[$method->getAvailabilityPlugin()];
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getPrice(SpyShipmentMethod $method, QuoteTransfer $quoteTransfer)
    {
        $price = $method->getDefaultPrice();
        $pricePlugins = $this->plugins[ShipmentDependencyProvider::PRICE_PLUGINS];

        if (isset($pricePlugins[$method->getPricePlugin()])) {
            $pricePlugin = $this->getPricePlugin($method, $pricePlugins);
            $price = $pricePlugin->getPrice($quoteTransfer);
        }

        return $price;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param array $pricePlugins
     *
     * @return \Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodPricePluginInterface
     */
    protected function getPricePlugin(SpyShipmentMethod $method, array $pricePlugins)
    {
        return $pricePlugins[$method->getPricePlugin()];
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getDeliveryTime(SpyShipmentMethod $method, QuoteTransfer $quoteTransfer)
    {
        $timeString = '';
        $deliveryTimePlugins = $this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS];

        if (isset($deliveryTimePlugins[$method->getDeliveryTimePlugin()])) {
            $deliveryTimePlugin = $this->getDeliveryTimePlugin($method, $deliveryTimePlugins);
            $timeString = $deliveryTimePlugin->getTime($quoteTransfer);
        }

        return $timeString;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param array $deliveryTimePlugins
     *
     * @return \Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodDeliveryTimePluginInterface
     */
    protected function getDeliveryTimePlugin(SpyShipmentMethod $method, array $deliveryTimePlugins)
    {
        return $deliveryTimePlugins[$method->getDeliveryTimePlugin()];
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     *
     * @return int
     */
    protected function getEffectiveTaxRate(SpyShipmentMethod $shipmentMethodEntity)
    {
        if (!$shipmentMethodEntity->getTaxSet()) {
            return 0;
        }

        $effectiveTaxRate = 0;
        foreach ($shipmentMethodEntity->getTaxSet()->getSpyTaxRates() as $taxRate) {
            $effectiveTaxRate = $taxRate->getRate();
        }

        return $effectiveTaxRate;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     *
     * @return string|null
     */
    protected function findShipmentCarrierName(SpyShipmentMethod $shipmentMethodEntity)
    {
        if (!$shipmentMethodEntity->getShipmentCarrier()) {
            return null;
        }

        return $shipmentMethodEntity->getShipmentCarrier()->getName();
    }

}
