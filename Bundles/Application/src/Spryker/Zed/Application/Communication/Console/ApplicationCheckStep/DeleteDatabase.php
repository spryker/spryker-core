<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Console\ApplicationCheckStep;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 */
class DeleteDatabase extends AbstractApplicationCheckStep
{

    /**
     * @return void
     */
    public function run()
    {
        $this->getFacade()->runCheckStepDeleteDatabase($this->logger);
    }

}
