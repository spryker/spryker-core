<?php

namespace SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep;

class ExportKeyValue extends AbstractApplicationCheckStep
{
    public function run()
    {
        $this->dependencyContainer->getApplicationFacade()->runCheckStepExportKeyValue($this->logger);
    }
}
