<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\StateMachine\Mocks;

class TestStateMachineHandlerException extends UnitStateMachineHandler
{

    /**
     * List of command plugins for this state machine for all processes.
     *
     * @return array
     */
    public function getCommandPlugins()
    {
        return [
            'Test/CreateInvoice' => new TestCommandPlugin(),
            'Test/SendInvoice' => new TestCommandExceptionPlugin(),
        ];
    }

    /**
     * List of condition plugins for this state machine for all processes.
     *
     * @return array
     */
    public function getConditionPlugins()
    {
        return [
            'Test/IsInvoiceSent' => new TestConditionPlugin(),
            'Test/Condition' => new TestConditionPlugin(),
        ];
    }

}
