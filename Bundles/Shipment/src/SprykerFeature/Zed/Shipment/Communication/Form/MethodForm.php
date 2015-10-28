<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Shared\Library\Currency\CurrencyManager;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use SprykerFeature\Zed\Shipment\ShipmentConfig;
use SprykerFeature\Zed\Shipment\ShipmentDependencyProvider;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
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
    const TAX_PLUGIN_FIELD = 'taxCalculationPlugin';
    const PRICE_CALCULATION_PLUGIN_FIELD = 'priceCalculationPlugin';
    const DELIVERY_TIME_PLUGIN_FIELD = 'deliveryTimePlugin';
    const CARRIER_FIELD = 'fkShipmentCarrier';
    const TAX_SET = 'fkTaxSet';

    /**
     * @var SpyShipmentMethodQuery
     */
    protected $methodQuery;

    /**
     * @var SpyShipmentCarrierQuery
     */
    protected $carrierQuery;

    /**
     * @var SpyTaxSetQuery
     */
    protected $taxSetQuery;

    /**
     * @var array
     */
    protected $plugins;

    /**
     * @var int|null
     */
    protected $idMethod;

    /**
     * @var ShipmentConfig
     */
    protected $shipmentConfig;

    /**
     * @param SpyShipmentMethodQuery $methodQuery
     * @param SpyShipmentCarrierQuery $carrierQuery
     * @param SpyTaxSetQuery $taxQuery
     * @param ShipmentConfig $shipmentConfig
     * @param array $plugins
     * @param null $idMethod
     */
    public function __construct(
        SpyShipmentMethodQuery $methodQuery,
        SpyShipmentCarrierQuery $carrierQuery,
        SpyTaxSetQuery $taxSetQuery,
        ShipmentConfig $shipmentConfig,
        array $plugins,
        $idMethod = null
    ) {
        $this->methodQuery = $methodQuery;
        $this->carrierQuery = $carrierQuery;
        $this->taxSetQuery = $taxSetQuery;
        $this->shipmentConfig = $shipmentConfig;
        $this->plugins = $plugins;
        $this->idMethod = $idMethod;
    }

    /**
     * @return self
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
            'label' => 'Name',
        ])
        ;
        $this->addAutosuggest(self::NAME_GLOSSARY_FIELD, [
            'label' => 'Name glossary key',
            'url' => '/glossary/ajax/keys',
        ])
        ;
        $this->addAutosuggest(self::DESCRIPTION_GLOSSARY_FIELD, [
            'label' => 'Description glossary key',
            'url' => '/glossary/ajax/keys',
        ])
        ;
        $this->addMoney(self::PRICE_FIELD, [
            'label' => 'Price',
        ])
        ;
        $this->addChoice(self::AVAILABILITY_PLUGIN_FIELD, [
            'label' => 'Availability Plugin',
            'placeholder' => 'Select one',
            'choice_list' => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS])
            ),
        ])
        ;
        $this->addChoice(self::PRICE_CALCULATION_PLUGIN_FIELD, [
            'label' => 'Price Calculation Plugin',
            'placeholder' => 'Select one',
            'choice_list' => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::PRICE_CALCULATION_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::PRICE_CALCULATION_PLUGINS])
            ),
        ])
        ;
        $this->addChoice(self::DELIVERY_TIME_PLUGIN_FIELD, [
            'label' => 'Delivery Time Plugin',
            'placeholder' => 'Select one',
            'choice_list' => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS])
            ),
        ])
        ;
        $this->addChoice(self::TAX_PLUGIN_FIELD, [
            'label' => 'Tax Calculation Plugin',
            'placeholder' => 'Select one',
            'choice_list' => new ChoiceList(
                array_keys($this->plugins[ShipmentDependencyProvider::TAX_CALCULATION_PLUGINS]),
                array_keys($this->plugins[ShipmentDependencyProvider::TAX_CALCULATION_PLUGINS])
            ),
        ])
        ;

        $this->addChoice(self::TAX_SET, [
            'label' => 'Tax Set',
            'placeholder' => 'Select one',
            'choices' => $this->loadTaxSets(),
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

        foreach ($carriers as $carrier) {
            $result[$carrier->getIdShipmentCarrier()] = $carrier->getName();
        }

        return $result;
    }

    /**
     * Set price vom euro to cent
     */
    public function getData()
    {
        $data = parent::getData();
        if (isset($data[self::PRICE_FIELD])) {
            $data[self::PRICE_FIELD] = CurrencyManager::getInstance()->convertDecimalToCent($data[self::PRICE_FIELD]);
            $data[self::PRICE_FIELD] = round($data[self::PRICE_FIELD]);
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        if (!is_null($this->idMethod)) {
            $method = $this->methodQuery->findOneByIdShipmentMethod($this->idMethod);

            $data = [
                self::ID_FIELD => $method->getIdShipmentMethod(),
                self::CARRIER_FIELD => $method->getFkShipmentCarrier(),
                self::NAME_FIELD => $method->getName(),
                self::NAME_GLOSSARY_FIELD => $method->getGlossaryKeyName(),
                self::DESCRIPTION_GLOSSARY_FIELD => $method->getGlossaryKeyDescription(),
                self::PRICE_FIELD => CurrencyManager::getInstance()->convertCentToDecimal($method->getPrice()),
                self::AVAILABILITY_PLUGIN_FIELD => $method->getAvailabilityPlugin(),
                self::PRICE_CALCULATION_PLUGIN_FIELD => $method->getPriceCalculationPlugin(),
                self::DELIVERY_TIME_PLUGIN_FIELD => $method->getDeliveryTimePlugin(),
                self::TAX_PLUGIN_FIELD => $method->getTaxCalculationPlugin(),
                self::IS_ACTIVE_FIELD => $method->getIsActive(),
            ];

            $taxSet = $method->getTaxSet();
            if (isset($taxSet)) {
                $data[self::TAX_SET] = $method->getTaxSet()->getIdTaxSet();
            }

            return $data;
        }

        return [];
    }

    /**
     * @return array
     */
    protected function loadTaxSets()
    {
        $taxSets = $this->taxSetQuery->find();
        $taxSetsArray = [];
        foreach ($taxSets as $taxSet) {
            $taxSetsArray[$taxSet->getIdTaxSet()] = $taxSet->getName();
        }

        return $taxSetsArray;
    }

}
