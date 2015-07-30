<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentCarrierQuery;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;
use SprykerFeature\Zed\Shipment\ShipmentConfig;
use SprykerFeature\Zed\Shipment\ShipmentDependencyProvider;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class MethodForm extends AbstractForm
{

    const NAME_FIELD = 'name';
    const NAME_GLOSSARY_FIELD = 'glossaryKeyName';
    const DESCRIPTION_GLOSSARY_FIELD = 'glossaryKeyDescription';
    const IS_ACTIVE_FIELD = 'isActive';
    const PRICE_FIELD = 'price';
    const AVAILABILITY_PLUGIN_FIELD = 'availabilityPlugin';
    const PRICE_CALCULATION_PLUGIN_FIELD = 'priceCalculationPlugin';
    const DELIVERY_TIME_PLUGIN_FIELD = 'deliveryTimePlugin';
    const CARRIER_FIELD = 'fkShipmentCarrier';

    /**
     * @var SpyShipmentMethodQuery
     */
    protected $methodQuery;

    /**
     * @var SpyShipmentCarrierQuery
     */
    protected $carrierQuery;

    /**
     * @var array
     */
    protected $plugins;

    /**
     * @param SpyShipmentMethodQuery $methodQuery
     * @param SpyShipmentCarrierQuery $carrierQuery
     * @param $plugins
     */
    public function __construct(
        SpyShipmentMethodQuery $methodQuery,
        SpyShipmentCarrierQuery $carrierQuery,
        $plugins
    ) {
        $this->methodQuery = $methodQuery;
        $this->carrierQuery = $carrierQuery;
        $this->plugins = $plugins;
    }

    /**
     * @return $this
     */
    protected function buildFormFields()
    {
        $this->addChoice(self::CARRIER_FIELD, [
            'label' => 'Carrier',
            'placeholder' => 'Select one',
            'choices' => $this->getCarrierOptions(),
        ])
        ;
        $this->addText(self::NAME_FIELD, [
            'label' => 'Name'
        ])
        ;
        $this->addAutosuggest(self::NAME_GLOSSARY_FIELD, [
            'label' => 'Name glossary key',
            'url' => '/glossary/ajax/keys'
        ])
        ;
        $this->addAutosuggest(self::DESCRIPTION_GLOSSARY_FIELD, [
            'label' => 'Description glossary key',
            'url' => '/glossary/ajax/keys'
        ])
        ;
        $this->addMoney(self::PRICE_FIELD, [
            'label' => 'Price'
        ])
        ;
        $this->addChoice(self::AVAILABILITY_PLUGIN_FIELD, [
            'label' => 'Availability Plugin',
            'placeholder' => 'Select one',
            'choice_list' => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS])
            )
        ])
        ;
        $this->addChoice(self::PRICE_CALCULATION_PLUGIN_FIELD, [
            'label' => 'Price Calculation Plugin',
            'placeholder' => 'Select one',
            'choice_list' => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::PRICE_CALCULATION_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::PRICE_CALCULATION_PLUGINS])
            )
        ])
        ;
        $this->addChoice(self::DELIVERY_TIME_PLUGIN_FIELD, [
            'label' => 'Delivery Time Plugin',
            'placeholder' => 'Select one',
            'choice_list' => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS])
            )
        ])
        ;
        $this->addCheckbox('isActive');

        return $this;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $result = [];

        return $result;
    }

    /**
     * @return array
     */
    protected function getCarrierOptions()
    {
        $carriers = $this->carrierQuery->filterByIsActive(true)->find();
        $result = [];

        if (empty($carriers) === false) {
            foreach ($carriers as $carrier) {
                $result[$carrier->getIdShipmentCarrier()] = $carrier->getName();
            }
        }

        return $result;
    }
}
