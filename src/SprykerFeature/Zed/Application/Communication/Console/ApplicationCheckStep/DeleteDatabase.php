<?php

namespace SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;

class DeleteDatabase extends AbstractApplicationCheckStep
{
    public function run()
    {
        $this->dependencyContainer->getApplicationFacade()->runCheckStepDeleteDatabase($this->logger);
    }
}
