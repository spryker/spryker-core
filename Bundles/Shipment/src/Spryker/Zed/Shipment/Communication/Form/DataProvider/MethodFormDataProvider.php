<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Form\DataProvider;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Shipment\Communication\Form\MethodForm;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class MethodFormDataProvider
{

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * @var array
     */
    protected $plugins;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     * @param \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface $taxQueryContainer
     * @param array $plugins
     */
    public function __construct(
        ShipmentQueryContainerInterface $shipmentQueryContainer,
        array $plugins
    ) {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
        $this->plugins = $plugins;
    }

    /**
     * @param int|null $idMethod
     *
     * @return array
     */
    public function getData($idMethod = null)
    {
        if ($idMethod !== null) {
            $method = $this->shipmentQueryContainer->queryMethods()->findOneByIdShipmentMethod($idMethod);

            $data = [
                MethodForm::FIELD_ID_FIELD => $method->getIdShipmentMethod(),
                MethodForm::FIELD_CARRIER_FIELD => $method->getFkShipmentCarrier(),
                MethodForm::FIELD_NAME_FIELD => $method->getName(),
                MethodForm::FIELD_DEFAULT_PRICE => $method->getDefaultPrice(),
                MethodForm::FIELD_AVAILABILITY_PLUGIN_FIELD => $method->getAvailabilityPlugin(),
                MethodForm::FIELD_PRICE_PLUGIN_FIELD => $method->getPricePlugin(),
                MethodForm::FIELD_DELIVERY_TIME_PLUGIN_FIELD => $method->getDeliveryTimePlugin(),
                MethodForm::FIELD_IS_ACTIVE => $method->getIsActive(),
            ];

            return $data;
        }

        return [];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = [
            MethodForm::OPTION_CARRIER_CHOICES => $this->getCarrierOptions(),
            MethodForm::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS])
            ),
            MethodForm::OPTION_PRICE_PLUGIN_CHOICE_LIST => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::PRICE_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::PRICE_PLUGINS])
            ),
            MethodForm::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS])
            ),
        ];

        return $options;
    }

    /**
     * @return array
     */
    protected function getCarrierOptions()
    {
        $carriers = $this->shipmentQueryContainer->queryCarriers()->filterByIsActive(true)->find();
        $result = [];

        foreach ($carriers as $carrier) {
            $result[$carrier->getIdShipmentCarrier()] = $carrier->getName();
        }

        return $result;
    }

}
