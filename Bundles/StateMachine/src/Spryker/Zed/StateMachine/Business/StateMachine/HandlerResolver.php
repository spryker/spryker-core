<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Spryker\Zed\StateMachine\Business\Exception\StateMachineHandlerNotFound;

class HandlerResolver implements HandlerResolverInterface
{
    /**
     * @var \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface[]
     */
    protected $handlers = [];

    /**
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface[] $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @param string $stateMachineName
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineHandlerNotFound
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface
     */
    public function get($stateMachineName)
    {
        $stateMachineHandler = $this->find($stateMachineName);
        if ($stateMachineHandler !== null) {
            return $stateMachineHandler;
        }

        throw new StateMachineHandlerNotFound(
            sprintf(
                'State machine handler with name "%s" not found',
                $stateMachineName
            )
        );
    }

    /**
     * @param string $stateMachineName
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface|null
     */
    public function find($stateMachineName)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->getStateMachineName() === $stateMachineName) {
                return $handler;
            }
        }

        return null;
    }
}
