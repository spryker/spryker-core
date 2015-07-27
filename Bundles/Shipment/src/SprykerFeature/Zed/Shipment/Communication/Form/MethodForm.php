<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;

class MethodForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';

    /**
     * @var SpyShipmentMethodQuery
     */
    protected $methodQuery;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param SpyShipmentMethodQuery $methodQuery
     * @param string $type
     */
    public function __construct(
        SpyShipmentMethodQuery $methodQuery,
        $type = self::ADD
    ) {
        $this->methodQuery = $methodQuery;
        $this->type = $type;
    }

    /**
     * @return $this
     */
    protected function buildFormFields()
    {
        /*$this->addChoice('default_billing_address', [
            'label' => 'Billing Address',
            'placeholder' => 'Select one',
            'choices' => $this->getAddressOptions(),
        ]);*/

        $this->addAutosuggest(
            'fkGlossaryKeyMethodName',
            [
                'url' => '/glossary/ajax/keys'
            ]
        );
        $this->addAutosuggest(
            'fkGlossaryKeyMethodDescription',
            [
                'url' => '/glossary/ajax/keys'
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
}
