<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Business\Carrier;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Shipment\Business\Exception\CarrierExistsException;

interface CarrierManagerInterface
{
    /**
     * @param ShipmentCarrierTransfer $carrierTransfer
     *
     * @return int
     * @throws CarrierExistsException
     * @throws \Exception
     * @throws PropelException
     */
    public function createCarrier($carrierTransfer);
}
