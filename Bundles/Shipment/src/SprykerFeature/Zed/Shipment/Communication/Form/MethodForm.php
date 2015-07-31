<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentCarrierQuery;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;
use SprykerFeature\Zed\Shipment\ShipmentDependencyProvider;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class MethodForm extends AbstractForm
{

    const NAME_FIELD = 'name';
    const ID_FIELD = 'idShipmentMethod';
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
     * @var int|null
     */
    protected $idMethod;

    /**
     * @param SpyShipmentMethodQuery $methodQuery
     * @param SpyShipmentCarrierQuery $carrierQuery
     * @param array $plugins
     * @param int|null $idMethod
     */
    public function __construct(
        SpyShipmentMethodQuery $methodQuery,
        SpyShipmentCarrierQuery $carrierQuery,
        array $plugins,
        $idMethod = null
    ) {
        $this->methodQuery = $methodQuery;
        $this->carrierQuery = $carrierQuery;
        $this->plugins = $plugins;
        $this->idMethod = $idMethod;
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

        if (!is_null($this->idMethod)) {
            $this->addHidden(self::ID_FIELD);
        }

        return $this;
    }


    /**
     * @return array
     */
    protected function getCarrierOptions()
    {
        $carriers = $this->carrierQuery->filterByIsActive(true)->find();
        $result = [];

        if (!empty($carriers)) {
            foreach ($carriers as $carrier) {
                $result[$carrier->getIdShipmentCarrier()] = $carrier->getName();
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        if (!is_null($this->idMethod)) {
            $method = $this->methodQuery->findOneByIdShipmentMethod($this->idMethod);

            return [
                self::ID_FIELD => $method->getIdShipmentMethod(),
                self::CARRIER_FIELD => $method->getFkShipmentCarrier(),
                self::NAME_FIELD => $method->getName(),
                self::NAME_GLOSSARY_FIELD => $method->getGlossaryKeyName(),
                self::DESCRIPTION_GLOSSARY_FIELD => $method->getGlossaryKeyDescription(),
                self::PRICE_FIELD => $method->getPrice(),
                self::AVAILABILITY_PLUGIN_FIELD => $method->getAvailabilityPlugin(),
                self::PRICE_CALCULATION_PLUGIN_FIELD => $method->getPriceCalculationPlugin(),
                self::DELIVERY_TIME_PLUGIN_FIELD => $method->getDeliveryTimePlugin(),
                self::IS_ACTIVE_FIELD => $method->getIsActive()
            ];
        }

        return [];
    }
}
