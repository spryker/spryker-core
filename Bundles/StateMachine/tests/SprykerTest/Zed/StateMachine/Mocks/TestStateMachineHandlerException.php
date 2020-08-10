<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Mocks;

class TestStateMachineHandlerException extends TestStateMachineHandler
{
    /**
     * List of command plugins for this state machine for all processes.
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface[]
     */
    public function getCommandPlugins(): array
    {
        return [
            'Test/CreateInvoice' => new TestCommandPlugin(),
            'Test/SendInvoice' => new TestCommandExceptionPlugin(),
        ];
    }

    /**
     * List of condition plugins for this state machine for all processes.
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface[]
     */
    public function getConditionPlugins(): array
    {
        return [
            'Test/IsInvoiceSent' => new TestConditionPlugin(),
            'Test/Condition' => new TestConditionPlugin(),
        ];
    }
}
