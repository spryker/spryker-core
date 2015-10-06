<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Business;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\SequenceNumberBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SequenceNumberBusiness getFactory()
 * @method SequenceNumberDependencyContainer getDependencyContainer()
 */
class SequenceNumberFacade extends AbstractFacade
{

    /***
     * @param SequenceNumberSettingsInterface $sequenceNumberSettings
     *
     * @return string
     */
    public function generate(SequenceNumberSettingsInterface $sequenceNumberSettings)
    {
        $sequenceNumber = $this->getDependencyContainer()
            ->createSequenceNumber($sequenceNumberSettings)
        ;

        return $sequenceNumber->generate();
    }

}
