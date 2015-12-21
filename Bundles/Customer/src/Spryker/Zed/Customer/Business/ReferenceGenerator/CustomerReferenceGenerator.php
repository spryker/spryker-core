<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Business\ReferenceGenerator;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberInterface;

class CustomerReferenceGenerator implements CustomerReferenceGeneratorInterface
{

    /**
     * @var CustomerToSequenceNumberInterface
     */
    protected $facadeSequenceNumber;

    /**
     * @var SequenceNumberSettingsTransfer
     */
    protected $sequenceNumberSettings;

    /**
     * @param CustomerToSequenceNumberInterface $sequenceNumberFacade
     * @param SequenceNumberSettingsTransfer $sequenceNumberSettings
     */
    public function __construct(
        CustomerToSequenceNumberInterface $sequenceNumberFacade,
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
