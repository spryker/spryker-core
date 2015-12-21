<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Dependency\Facade;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;

class CustomerToSequenceNumberBridge implements CustomerToSequenceNumberInterface
{

    /**
     * @var \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade
     */
    protected $sequenceNumberFacade;

    /**
     * @param \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade $sequenceNumberFacade
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
