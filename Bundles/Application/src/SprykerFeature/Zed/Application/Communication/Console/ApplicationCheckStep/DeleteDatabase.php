<?php

namespace SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Application\Business\ApplicationFacade;

/**
 * @method ApplicationFacade getFacade()
 */
class DeleteDatabase extends AbstractApplicationCheckStep
{
    public function run()
    {
        $this->getFacade()->runCheckStepDeleteDatabase($this->logger);
    }
}
