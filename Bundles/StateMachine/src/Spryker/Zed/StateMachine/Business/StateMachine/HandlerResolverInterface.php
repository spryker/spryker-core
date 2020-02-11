<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

interface HandlerResolverInterface
{
    /**
     * @param string $stateMachineName
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineHandlerNotFound
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface
     */
    public function get($stateMachineName);

    /**
     * @param string $stateMachineName
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface|null
     */
    public function find($stateMachineName);
}
