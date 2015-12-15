<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade;

class OrderReferenceGenerator implements OrderReferenceGeneratorInterface
{

    /** @var SequenceNumberFacade */
    protected $facadeSequenceNumber;

    /** @var SequenceNumberSettingsTransfer */
    protected $sequenceNumberSettings;

    /**
     * @param SequenceNumberFacade $sequenceNumberFacade
     * @param SequenceNumberSettingsTransfer $sequenceNumberSettings
     */
    public function __construct(
        SequenceNumberFacade $sequenceNumberFacade,
        SequenceNumberSettingsTransfer $sequenceNumberSettings
    ) {
        $this->facadeSequenceNumber = $sequenceNumberFacade;
        $this->sequenceNumberSettings = $sequenceNumberSettings;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function generateOrderReference(OrderTransfer $orderTransfer)
    {
        return $this->facadeSequenceNumber->generate($this->sequenceNumberSettings);
    }

}
