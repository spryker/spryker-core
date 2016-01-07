<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Dependency\Facade;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade;

class CustomerToSequenceNumberBridge implements CustomerToSequenceNumberInterface
{

    /**
     * @var SequenceNumberFacade
     */
    protected $sequenceNumberFacade;

    /**
     * @param SequenceNumberFacade $sequenceNumberFacade
     */
    public function __construct($sequenceNumberFacade)
    {
        $this->sequenceNumberFacade = $sequenceNumberFacade;
    }

    /**
     * @param SequenceNumberSettingsTransfer $sequenceNumberSettings
     *
     * @return string
     */
    public function generate(SequenceNumberSettingsTransfer $sequenceNumberSettings)
    {
        return $this->sequenceNumberFacade->generate($sequenceNumberSettings);
    }

}
