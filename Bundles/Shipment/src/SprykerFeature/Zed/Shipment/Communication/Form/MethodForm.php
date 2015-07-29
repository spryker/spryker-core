<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentCarrierQuery;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;
use SprykerFeature\Zed\Shipment\ShipmentDependencyProvider;

/**
 * @method ShipmentDependencyProvider getD
 */
class MethodForm extends AbstractForm
{

    const NAME_FIELD = 'name';
    const NAME_GLOSSARY_FIELD = 'glossaryKeyName';
    const DESCRIPTION_GLOSSARY_FIELD = 'glossaryKeyDescription';
    const IS_ACTIVE_FIELD = 'isActive';
    const PRICE_FIELD = 'price';
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
     * @param SpyShipmentMethodQuery $methodQuery
     * @param SpyShipmentCarrierQuery $carrierQuery
     * @param string $type
     */
    public function __construct(SpyShipmentMethodQuery $methodQuery, SpyShipmentCarrierQuery $carrierQuery)
    {
        $this->methodQuery = $methodQuery;
        $this->carrierQuery = $carrierQuery;
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
        $carriers = $this->carrierQuery->find();
        $result = [];

        if (empty($carriers) === false) {
            foreach ($carriers as $carrier) {
                $result[$carrier->getIdShipmentCarrier()] = $carrier->getName();
            }
        }

        return $result;
    }
}
