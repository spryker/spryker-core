<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Shipment\Persistence\Propel\ShipmentCarrierQuery;

class CarrierForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';

    /**
     * @var ShipmentCarrierQuery
     */
    protected $carrierQuery;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param ShipmentCarrierQuery $carrierQuery
     * @param string $type
     */
    public function __construct(
        ShipmentCarrierQuery $carrierQuery,
        $type = self::ADD
    ) {
        $this->carrierQuery = $carrierQuery;
        $this->type = $type;
    }

    /**
     * @return $this
     */
    protected function buildFormFields()
    {
        $this->addAutosuggest(
            'carrier_name',
            [
                'url' => '/glossary/ajax/keys'
            ]
        );

        $this->addSubmit(
            'submit',
            [
                'label' => $this->type,
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]
        );

        return $this;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {

    }
}
