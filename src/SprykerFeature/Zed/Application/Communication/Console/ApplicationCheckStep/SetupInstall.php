<?php

namespace SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep;

class SetupInstall extends AbstractApplicationCheckStep
{
    public function run()
    {
        $this->dependencyContainer->getApplicationFacade()->runCheckStepSetupInstall($this->logger);
    }
}
