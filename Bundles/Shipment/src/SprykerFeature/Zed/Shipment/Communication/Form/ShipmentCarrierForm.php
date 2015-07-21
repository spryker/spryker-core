<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentCarrierQuery;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ShipmentCarrierForm extends AbstractForm
{
    /**
     * @var SpyShipmentCarrierQuery
     */
    protected $shipmentCarrierQuery;

    /**
     * @param $shipmentCarrierQuery
     */
    public function __construct(SpyShipmentCarrierQuery $shipmentCarrierQuery)
    {
        $this->shipmentCarrierQuery = $shipmentCarrierQuery;
    }

    /**
     * @return $this
     */
    protected function buildFormFields()
    {
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {

    }
}
