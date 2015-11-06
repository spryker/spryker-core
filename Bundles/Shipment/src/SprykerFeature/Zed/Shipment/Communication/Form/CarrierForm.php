<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;

class CarrierForm extends AbstractForm
{

    const NAME_GLOSSARY_FIELD = 'glossaryKeyName';
    const NAME_FIELD = 'name';
    const IS_ACTIVE_FIELD = 'isActive';
    const CARRIER_ID = 'carrier_id';

    /**
     * @var SpyShipmentCarrierQuery
     */
    protected $carrierQuery;

    /**
     * @param SpyShipmentCarrierQuery $carrierQuery
     */
    public function __construct(SpyShipmentCarrierQuery $carrierQuery)
    {
        $this->carrierQuery = $carrierQuery;
    }

    /**
     * @return self
     */
    protected function buildFormFields()
    {
        $this->addText(self::NAME_FIELD, [
                'label' => 'Name',
            ])
        ;
        $this->addAutosuggest(self::NAME_GLOSSARY_FIELD, [
                'label' => 'Name glossary key',
                'url' => '/glossary/ajax/keys',
            ])
        ;
        $this->addCheckbox(self::IS_ACTIVE_FIELD, [
                'label' => 'Enabled?',
            ])
        ;

        return $this;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $result = [];
        $carrierId = $this->request->get(self::CARRIER_ID);

        if ($carrierId !== null) {
            $carrier = $this->carrierQuery->findOneByIdShipmentCarrier($carrierId);
            $result = [
                self::NAME_FIELD => $carrier->getFkGlossaryKeyCarrierName(),
                self::IS_ACTIVE_FIELD => $carrier->getIsActive(),
            ];
        }

        return $result;
    }

}
