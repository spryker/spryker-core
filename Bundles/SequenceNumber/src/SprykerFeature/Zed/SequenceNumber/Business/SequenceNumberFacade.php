<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Business;

use Generated\Shared\Shipment\ShipmentInterface;
use Generated\Shared\Shipment\ShipmentMethodAvailabilityInterface;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\SequenceNumberBusiness;
use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SequenceNumberBusiness getFactory()
 * @method SequenceNumberDependencyContainer getDependencyContainer()
 */
class SequenceNumberFacade extends AbstractFacade
{

    /***
     * @return string
     */
    public function generate()
    {
        $sequenceNumber = $this->getDependencyContainer()
            ->createSequenceNumber()
        ;

        return $sequenceNumber->generate();
    }

}
