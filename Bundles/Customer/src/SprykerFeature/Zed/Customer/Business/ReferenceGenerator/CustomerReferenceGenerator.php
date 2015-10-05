<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business\ReferenceGenerator;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use SprykerFeature\Zed\SequenceNumber\Business\SequenceNumberFacade;

class CustomerReferenceGenerator implements CustomerReferenceGeneratorInterface
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
     * @param CustomerInterface $orderTransfer
     *
     * @return string
     */
    public function generateCustomerReference(CustomerInterface $orderTransfer)
    {
        return $this->facadeSequenceNumber->generate($this->sequenceNumberSettings);
    }

}
