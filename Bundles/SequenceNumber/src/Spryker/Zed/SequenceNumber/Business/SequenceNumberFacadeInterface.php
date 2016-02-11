<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */
namespace Spryker\Zed\SequenceNumber\Business;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;

interface SequenceNumberFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\SequenceNumberSettingsTransfer $sequenceNumberSettings
     *
     * @return string
     */
    public function generate(SequenceNumberSettingsTransfer $sequenceNumberSettings);

}
