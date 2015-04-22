<?php

namespace SprykerFeature\Zed\Application\Communication\Console\ApplicationCheckStep;

class ExportSearch extends AbstractApplicationCheckStep
{
    public function run()
    {
        $this->dependencyContainer->getApplicationFacade()->runCheckStepExportSearch($this->logger);
    }
}
