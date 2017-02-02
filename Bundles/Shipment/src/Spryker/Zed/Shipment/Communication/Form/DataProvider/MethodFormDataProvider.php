<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Form\DataProvider;

use Spryker\Zed\Shipment\Communication\Form\MethodForm;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

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
     * @var \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface
     */
    protected $taxFacade;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     * @param \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface $taxFacade
     * @param array $plugins
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface $moneyFacade
     */
    public function __construct(
        ShipmentQueryContainerInterface $shipmentQueryContainer,
        ShipmentToTaxInterface $taxFacade,
        array $plugins,
        ShipmentToMoneyInterface $moneyFacade
    ) {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
        $this->taxFacade = $taxFacade;
        $this->plugins = $plugins;
        $this->moneyFacade = $moneyFacade;
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
                MethodForm::FIELD_TAX_SET_FIELD => $method->getFkTaxSet(),
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
            MethodForm::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST => $this->getPluginOptions(ShipmentDependencyProvider::AVAILABILITY_PLUGINS),
            MethodForm::OPTION_PRICE_PLUGIN_CHOICE_LIST => $this->getPluginOptions(ShipmentDependencyProvider::PRICE_PLUGINS),
            MethodForm::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST => $this->getPluginOptions(ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS),
            MethodForm::OPTION_TAX_SETS => $this->createTaxSetsList(),
        ];

        $options[MethodForm::OPTION_MONEY_FACADE] = $this->moneyFacade;

        return $options;
    }

    /**
     * @return array
     */
    protected function createTaxSetsList()
    {
        $taxSetCollection = $this->taxFacade->getTaxSets();
        if (!$taxSetCollection) {
            return [];
        }

        $taxSetList = [];
        foreach ($taxSetCollection->getTaxSets() as $taxSetTransfer) {
            $taxSetList[$taxSetTransfer->getIdTaxSet()] = $taxSetTransfer->getName();
        }

        return $taxSetList;
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

    /**
     * @param string $pluginsType
     *
     * @return array
     */
    private function getPluginOptions($pluginsType)
    {
        $plugins = array_keys($this->plugins[$pluginsType]);

        return array_combine($plugins, $plugins);
    }

}
