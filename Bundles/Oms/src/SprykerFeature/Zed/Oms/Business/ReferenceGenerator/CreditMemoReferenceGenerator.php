<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\ReferenceGenerator;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use SprykerFeature\Zed\SequenceNumber\Business\SequenceNumberFacade;

class CreditMemoReferenceGenerator implements CreditMemoReferenceGeneratorInterface
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
     * @return string
     */
    public function generateCreditMemoReference()
    {
        return $this->facadeSequenceNumber->generate($this->sequenceNumberSettings);
    }

}
