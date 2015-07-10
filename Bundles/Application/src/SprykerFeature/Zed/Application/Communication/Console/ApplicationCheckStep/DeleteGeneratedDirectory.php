<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep;

use SprykerFeature\Zed\Application\Business\ApplicationFacade;

/**
 * @method ApplicationFacade getFacade()
 */
class DeleteGeneratedDirectory extends AbstractApplicationCheckStep
{

    public function run()
    {
        $this->getFacade()->runCheckStepDeleteGeneratedDirectory($this->logger);
    }

}
