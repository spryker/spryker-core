<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Mocks;

use Exception;
use Generated\Shared\Transfer\StateMachineItemTransfer;

class TestCommandExceptionPlugin extends TestCommandPlugin
{
    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function run(StateMachineItemTransfer $stateMachineItemTransfer): bool
    {
        $this->throwTestException();

        return true;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    protected function throwTestException(): void
    {
        throw new Exception('Sry, something went wrong');
    }
}
