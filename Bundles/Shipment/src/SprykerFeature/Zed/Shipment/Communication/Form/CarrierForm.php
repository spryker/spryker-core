<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentCarrierQuery;

class CarrierForm extends AbstractForm
{
    const ADD = 'add';
    const UPDATE = 'update';
    const NAME_GLOSSARY_FIELD = 'fkGlossaryKeyCarrierName';
    const NAME_FIELD = 'name';
    const IS_ACTIVE_FIELD = 'isActive';
    const CARRIER_ID = 'carrier_id';

    /**
     * @var SpyShipmentCarrierQuery
     */
    protected $carrierQuery;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param SpyShipmentCarrierQuery $carrierQuery
     * @param string $type
     */
    public function __construct(
        SpyShipmentCarrierQuery $carrierQuery,
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
        $this->addText(
            self::NAME_FIELD,
            [
                'label' => 'Name'
            ]
        );
        $this->addAutosuggest(
            self::NAME_GLOSSARY_FIELD,
            [
                'label' => 'Name glossary key',
                'url' => '/glossary/ajax/keys'
            ]
        );
        $this->addCheckbox(
            self::IS_ACTIVE_FIELD,
            [
                'label' => 'Enabled?'
            ]
        );

        return $this;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $result = [];
        $carrierId = $this->request->get(self::CARRIER_ID);

        if (is_null($carrierId) === false) {
            $carrier = $this
                ->carrierQuery
                ->findOneByIdShipmentCarrier($carrierId)
            ;
            $result = [
                self::NAME_FIELD => $carrier->getFkGlossaryKeyCarrierName(),
                self::IS_ACTIVE_FIELD => $carrier->getIsActive()
            ];
        }

        return $result;
    }
}
