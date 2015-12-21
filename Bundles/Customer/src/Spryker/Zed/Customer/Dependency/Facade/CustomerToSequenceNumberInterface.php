<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Dependency\Facade;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;

interface CustomerToSequenceNumberInterface
{

    /**
     * @param SequenceNumberSettingsTransfer $sequenceNumberSettings
     *
     * @return string
     */
    public function generate(SequenceNumberSettingsTransfer $sequenceNumberSettings);

}
