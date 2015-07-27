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

    const ADD = 'add';
    const UPDATE = 'update';
    const NAME_FIELD = 'fkGlossaryKeyMethodName';
    const DESCRIPTION_FIELD = 'fkGlossaryKeyMethodDescription';
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
     * @var string
     */
    protected $type;

    /**
     * @param SpyShipmentMethodQuery $methodQuery
     * @param SpyShipmentCarrierQuery $carrierQuery
     * @param string $type
     */
    public function __construct(
        SpyShipmentMethodQuery $methodQuery,
        SpyShipmentCarrierQuery $carrierQuery,
        $type = self::ADD
    ) {
        $this->methodQuery = $methodQuery;
        $this->carrierQuery = $carrierQuery;
        $this->type = $type;
    }

    /**
     * @return $this
     */
    protected function buildFormFields()
    {
        $this->addChoice(
            self::CARRIER_FIELD,
            [
                'label' => 'Carrier',
                'placeholder' => 'Select one',
                'choices' => $this->getCarrierOptions(),
            ]
        );
        $this->addAutosuggest(
            self::NAME_FIELD,
            [
                'label' => 'Name',
                'url' => '/glossary/ajax/keys'
            ]
        );
        $this->addAutosuggest(
            self::DESCRIPTION_FIELD,
            [
                'label' => 'Description',
                'url' => '/glossary/ajax/keys'
            ]
        );
        $this->addMoney(
            self::PRICE_FIELD,
            [
                'label' => 'Price'
            ]
        );
        $this->addCheckbox('isActive');

        return $this;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {

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
                $result[$carrier->getIdShipmentCarrier()]
                    = $carrier->getSpyGlossaryKey()->getKey();
            }
        }

        return $result;
    }
}
