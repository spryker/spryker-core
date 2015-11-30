<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business\ReferenceGenerator;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use SprykerFeature\Zed\SequenceNumber\Business\SequenceNumberFacade;

class CustomerReferenceGenerator implements CustomerReferenceGeneratorInterface
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
     * @param CustomerTransfer $orderTransfer
     *
     * @return string
     */
    public function generateCustomerReference(CustomerTransfer $orderTransfer)
    {
        return $this->facadeSequenceNumber->generate($this->sequenceNumberSettings);
    }

}
