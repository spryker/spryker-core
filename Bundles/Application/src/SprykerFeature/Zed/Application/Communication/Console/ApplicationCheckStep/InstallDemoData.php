<?php

namespace SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep;

class InstallDemoData extends AbstractApplicationCheckStep
{
    public function run()
    {
        $this->dependencyContainer->getApplicationFacade()->runCheckStepInstallDemoData($this->logger);
    }
}
