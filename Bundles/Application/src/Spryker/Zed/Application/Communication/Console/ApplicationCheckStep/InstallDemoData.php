<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Console\ApplicationCheckStep;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 */
class InstallDemoData extends AbstractApplicationCheckStep
{

    /**
     * @return void
     */
    public function run()
    {
        $this->getFacade()->runCheckStepInstallDemoData($this->logger);
    }

}
