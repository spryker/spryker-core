<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;

class SalesToSequenceNumberBridge implements SalesToSequenceNumberInterface
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
     * @param SequenceNumberSettingsTransfer $sequenceNumberSettingsTransfer
     *
     * @return string
     */
    public function generate(SequenceNumberSettingsTransfer $sequenceNumberSettingsTransfer)
    {
        return $this->sequenceNumberFacade->generate($sequenceNumberSettingsTransfer);
    }

}
