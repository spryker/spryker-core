<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentCarrierQuery;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;
use SprykerFeature\Zed\Shipment\ShipmentConfig;
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
     * @var ShipmentConfig
     */
    protected $config;

    /**
     * @param SpyShipmentMethodQuery $methodQuery
     * @param SpyShipmentCarrierQuery $carrierQuery
     * @param ShipmentConfig $config
     */
    public function __construct(
        SpyShipmentMethodQuery $methodQuery,
        SpyShipmentCarrierQuery $carrierQuery,
        ShipmentConfig $config
    ) {
        $this->methodQuery = $methodQuery;
        $this->carrierQuery = $carrierQuery;
        $this->config = $config;
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
                array_keys($this->config->getAvailabilityPlugins()),
                array_keys($this->config->getAvailabilityPlugins())
            )
        ])
        ;
        $this->addChoice(self::PRICE_CALCULATION_PLUGIN_FIELD, [
            'label' => 'Price Calculation Plugin',
            'placeholder' => 'Select one',
            'choice_list' => new ChoiceList(
                array_keys($this->config->getPriceCalculationPlugins()),
                array_keys($this->config->getPriceCalculationPlugins())
            )
        ])
        ;
        $this->addChoice(self::DELIVERY_TIME_PLUGIN_FIELD, [
            'label' => 'Delivery Time Plugin',
            'placeholder' => 'Select one',
            'choice_list' => new ChoiceList(
                array_keys($this->config->getDeliveryTimePlugins()),
                array_keys($this->config->getDeliveryTimePlugins())
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
