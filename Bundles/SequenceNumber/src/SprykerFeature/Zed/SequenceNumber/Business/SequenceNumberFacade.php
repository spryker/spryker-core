<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Business;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SequenceNumberDependencyContainer getDependencyContainer()
 */
class SequenceNumberFacade extends AbstractFacade
{

    /***
     * @param SequenceNumberSettingsTransfer $sequenceNumberSettings
     *
     * @return string
     */
    public function generate(SequenceNumberSettingsTransfer $sequenceNumberSettings)
    {
        $sequenceNumber = $this->getDependencyContainer()
            ->createSequenceNumber($sequenceNumberSettings);

        return $sequenceNumber->generate();
    }

}
