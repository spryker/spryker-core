<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SequenceNumber\Business;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SequenceNumberDependencyContainer getBusinessFactory()
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
        $sequenceNumber = $this->getBusinessFactory()
            ->createSequenceNumber($sequenceNumberSettings);

        return $sequenceNumber->generate();
    }

}
