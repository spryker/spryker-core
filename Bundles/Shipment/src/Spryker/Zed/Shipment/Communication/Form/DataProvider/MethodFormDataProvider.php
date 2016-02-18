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
     * @var \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface
     */
    protected $taxQueryContainer;

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
        TaxQueryContainerInterface $taxQueryContainer,
        array $plugins
    ) {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
        $this->taxQueryContainer = $taxQueryContainer;
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
                MethodForm::FIELD_NAME_GLOSSARY_FIELD => $method->getGlossaryKeyName(),
                MethodForm::FIELD_DESCRIPTION_GLOSSARY_FIELD => $method->getGlossaryKeyDescription(),
                MethodForm::FIELD_PRICE_FIELD => CurrencyManager::getInstance()->convertCentToDecimal($method->getPrice()),
                MethodForm::FIELD_AVAILABILITY_PLUGIN_FIELD => $method->getAvailabilityPlugin(),
                MethodForm::FIELD_PRICE_CALCULATION_PLUGIN_FIELD => $method->getPriceCalculationPlugin(),
                MethodForm::FIELD_DELIVERY_TIME_PLUGIN_FIELD => $method->getDeliveryTimePlugin(),
                MethodForm::FIELD_TAX_PLUGIN_FIELD => $method->getTaxCalculationPlugin(),
                MethodForm::FIELD_IS_ACTIVE => $method->getIsActive(),
            ];

            $taxSet = $method->getTaxSet();
            if (isset($taxSet)) {
                $data[MethodForm::FIELD_TAX_SET] = $method->getTaxSet()->getIdTaxSet();
            }

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
            MethodForm::OPTION_TAX_SET_CHOICES => $this->loadTaxSets(),
            MethodForm::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS])
            ),
            MethodForm::OPTION_PRICE_CALCULATION_PLUGIN_CHOICE_LIST => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::PRICE_CALCULATION_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::PRICE_CALCULATION_PLUGINS])
            ),
            MethodForm::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS])
            ),
            MethodForm::OPTION_TAX_PLUGIN_CHOICE_LIST => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::TAX_CALCULATION_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::TAX_CALCULATION_PLUGINS])
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

    /**
     * @return array
     */
    protected function loadTaxSets()
    {
        $taxSets = $this->taxQueryContainer->queryAllTaxSets()->find();
        $taxSetsArray = [];
        foreach ($taxSets as $taxSet) {
            $taxSetsArray[$taxSet->getIdTaxSet()] = $taxSet->getName();
        }

        return $taxSetsArray;
    }

}
