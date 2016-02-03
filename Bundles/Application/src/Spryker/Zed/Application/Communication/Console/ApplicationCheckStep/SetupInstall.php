<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Console\ApplicationCheckStep;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 */
class SetupInstall extends AbstractApplicationCheckStep
{

    /**
     * @return void
     */
    public function run()
    {
        $this->getFacade()->runCheckStepSetupInstall($this->logger);
    }

}
