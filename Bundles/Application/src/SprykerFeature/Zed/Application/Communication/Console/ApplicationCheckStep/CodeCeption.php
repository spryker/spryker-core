<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Console\ApplicationCheckStep;

use Spryker\Zed\Application\Business\ApplicationFacade;

/**
 * @method ApplicationFacade getFacade()
 */
class CodeCeption extends AbstractApplicationCheckStep
{

    /**
     * @return void
     */
    public function run()
    {
        $this->getFacade()->runCheckStepCodeCeption($this->logger);
    }

}
