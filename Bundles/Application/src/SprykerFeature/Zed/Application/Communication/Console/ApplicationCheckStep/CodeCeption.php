<?php

namespace SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep;

class CodeCeption extends AbstractApplicationCheckStep
{
    public function run()
    {
        $this->dependencyContainer->getApplicationFacade()->runCheckStepCodeCeption($this->logger);
    }
}
