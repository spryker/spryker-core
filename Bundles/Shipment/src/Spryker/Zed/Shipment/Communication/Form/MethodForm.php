<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Shipment\ShipmentConfig;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;

class MethodForm extends AbstractForm
{

    const FIELD_NAME_FIELD = 'name';
    const FIELD_ID_FIELD = 'idShipmentMethod';
    const FIELD_NAME_GLOSSARY_FIELD = 'glossaryKeyName';
    const FIELD_DESCRIPTION_GLOSSARY_FIELD = 'glossaryKeyDescription';
    const IS_ACTIVE_FIELD = 'isActive';
    const FIELD_PRICE_FIELD = 'price';
    const FIELD_AVAILABILITY_PLUGIN_FIELD = 'availabilityPlugin';
    const FIELD_TAX_PLUGIN_FIELD = 'taxCalculationPlugin';
    const FIELD_PRICE_CALCULATION_PLUGIN_FIELD = 'priceCalculationPlugin';
    const FIELD_DELIVERY_TIME_PLUGIN_FIELD = 'deliveryTimePlugin';
    const FIELD_CARRIER_FIELD = 'fkShipmentCarrier';
    const FIELD_TAX_SET = 'fkTaxSet';

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
     * @param SpyTaxSetQuery $taxSetQuery
     * @param ShipmentConfig $shipmentConfig
     * @param array $plugins
     * @param int|null $idMethod
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
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'method';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_CARRIER_FIELD, 'choice', [
                'label' => 'Carrier',
                'placeholder' => 'Select one',
                'choices' => $this->getCarrierOptions(),
            ])
            ->add(self::FIELD_NAME_FIELD, 'text', [
                'label' => 'Name',
            ])
            ->add(self::FIELD_NAME_GLOSSARY_FIELD, new AutosuggestType(), [
                'label' => 'Name glossary key',
                'url' => '/glossary/ajax/keys',
            ])
            ->add(self::FIELD_DESCRIPTION_GLOSSARY_FIELD, new AutosuggestType(), [
                'label' => 'Description glossary key',
                'url' => '/glossary/ajax/keys',
            ])
            ->add(self::FIELD_PRICE_FIELD, 'money', [
                'label' => 'Price',
            ])
            ->add(self::FIELD_AVAILABILITY_PLUGIN_FIELD, 'choice', [
                'label' => 'Availability Plugin',
                'placeholder' => 'Select one',
                'choice_list' => new ChoiceList(
                    array_keys($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS]),
                    array_keys($this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS])
                ),
            ])
            ->add(self::FIELD_PRICE_CALCULATION_PLUGIN_FIELD, 'choice', [
                'label' => 'Price Calculation Plugin',
                'placeholder' => 'Select one',
                'choice_list' => new ChoiceList(
                    array_keys($this->plugins[ShipmentDependencyProvider::PRICE_CALCULATION_PLUGINS]),
                    array_keys($this->plugins[ShipmentDependencyProvider::PRICE_CALCULATION_PLUGINS])
                ),
            ])
            ->add(self::FIELD_DELIVERY_TIME_PLUGIN_FIELD, 'choice', [
                'label' => 'Delivery Time Plugin',
                'placeholder' => 'Select one',
                'choice_list' => new ChoiceList(
                    array_keys($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS]),
                    array_keys($this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS])
                ),
            ])
            ->add(self::FIELD_TAX_PLUGIN_FIELD, 'choice', [
                'label' => 'Tax Calculation Plugin',
                'placeholder' => 'Select one',
                'choice_list' => new ChoiceList(
                    array_keys($this->plugins[ShipmentDependencyProvider::TAX_CALCULATION_PLUGINS]),
                    array_keys($this->plugins[ShipmentDependencyProvider::TAX_CALCULATION_PLUGINS])
                ),
            ])
            ->add(self::FIELD_TAX_SET, 'choice', [
                'label' => 'Tax Set',
                'placeholder' => 'Select one',
                'choices' => $this->loadTaxSets(),
            ])
            ->add('isActive', 'checkbox');

        if ($this->idMethod !== null) {
            $builder->add(self::FIELD_ID_FIELD, 'hidden');
        }
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
     * @return array
     */
    public function populateFormFields()
    {
        if ($this->idMethod !== null) {
            $method = $this->methodQuery->findOneByIdShipmentMethod($this->idMethod);

            $data = [
                self::FIELD_ID_FIELD => $method->getIdShipmentMethod(),
                self::FIELD_CARRIER_FIELD => $method->getFkShipmentCarrier(),
                self::FIELD_NAME_FIELD => $method->getName(),
                self::FIELD_NAME_GLOSSARY_FIELD => $method->getGlossaryKeyName(),
                self::FIELD_DESCRIPTION_GLOSSARY_FIELD => $method->getGlossaryKeyDescription(),
                self::FIELD_PRICE_FIELD => CurrencyManager::getInstance()->convertCentToDecimal($method->getPrice()),
                self::FIELD_AVAILABILITY_PLUGIN_FIELD => $method->getAvailabilityPlugin(),
                self::FIELD_PRICE_CALCULATION_PLUGIN_FIELD => $method->getPriceCalculationPlugin(),
                self::FIELD_DELIVERY_TIME_PLUGIN_FIELD => $method->getDeliveryTimePlugin(),
                self::FIELD_TAX_PLUGIN_FIELD => $method->getTaxCalculationPlugin(),
                self::IS_ACTIVE_FIELD => $method->getIsActive(),
            ];

            $taxSet = $method->getTaxSet();
            if (isset($taxSet)) {
                $data[self::FIELD_TAX_SET] = $method->getTaxSet()->getIdTaxSet();
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
