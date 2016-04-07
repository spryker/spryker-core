<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Spryker\Zed\StateMachine\Business\Exception\StateMachineHandlerNotFound;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;

interface HandlerResolverInterface
{
    /**
     * @param string $stateMachineName
     *
     * @throws StateMachineHandlerNotFound
     * @return StateMachineHandlerInterface
     */
    public function findHandler($stateMachineName);
}
