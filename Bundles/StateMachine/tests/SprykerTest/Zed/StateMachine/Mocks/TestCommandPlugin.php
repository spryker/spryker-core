<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Mocks;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface;

class TestCommandPlugin implements CommandPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function run(StateMachineItemTransfer $stateMachineItemTransfer): bool
    {
        return true;
    }
}
