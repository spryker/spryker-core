<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;

interface SalesToSequenceNumberInterface
{

    /***
     * @param SequenceNumberSettingsTransfer $sequenceNumberSettingsTransfer
     *
     * @return string
     */
    public function generate(SequenceNumberSettingsTransfer $sequenceNumberSettingsTransfer);

}
