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
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberInterface $sequenceNumberFacade
     * @param \Generated\Shared\Transfer\SequenceNumberSettingsTransfer $sequenceNumberSettings
     */
    public function __construct(
        CustomerToSequenceNumberInterface $sequenceNumberFacade,
        SequenceNumberSettingsTransfer $sequenceNumberSettings
    ) {
        $this->facadeSequenceNumber = $sequenceNumberFacade;
        $this->sequenceNumberSettings = $sequenceNumberSettings;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $orderTransfer
     *
     * @return string
     */
    public function generateCustomerReference(CustomerTransfer $orderTransfer)
    {
        return $this->facadeSequenceNumber->generate($this->sequenceNumberSettings);
    }

}
