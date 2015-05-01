<?php

namespace SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep;

class DeleteGeneratedDirectory extends AbstractApplicationCheckStep
{
    public function run()
    {
        $this->dependencyContainer->getApplicationFacade()->runCheckStepDeleteGeneratedDirectory($this->logger);
    }
}
