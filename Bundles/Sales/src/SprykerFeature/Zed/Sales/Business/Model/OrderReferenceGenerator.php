<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\SequenceNumber\Business\SequenceNumberFacade;

class OrderReferenceGenerator implements OrderReferenceGeneratorInterface
{

    /** @var SequenceNumberFacade */
    protected $facadeSequenceNumber;

    /** @var SequenceNumberSettingsInterface */
    protected $sequenceNumberSettings;

    /**
     * @param SequenceNumberFacade $sequenceNumberFacade
     * @param SequenceNumberSettingsInterface $sequenceNumberSettings
     */
    public function __construct(
        SequenceNumberFacade $sequenceNumberFacade,
        SequenceNumberSettingsInterface $sequenceNumberSettings
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
