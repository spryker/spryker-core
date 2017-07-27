<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\StateMachine\Mocks;

use Exception;
use Generated\Shared\Transfer\StateMachineItemTransfer;

class TestCommandExceptionPlugin extends UnitCommandPlugin
{

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function run(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $this->throwTestException();

        return true;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    protected function throwTestException()
    {
        throw new Exception('Sry, something went wrong');
    }

}
