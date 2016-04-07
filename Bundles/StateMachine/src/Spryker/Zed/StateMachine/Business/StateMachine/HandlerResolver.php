<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Spryker\Zed\StateMachine\Business\Exception\StateMachineHandlerNotFound;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;

class HandlerResolver implements HandlerResolverInterface
{
    /**
     * @var array|StateMachineHandlerInterface[]
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
     * @throws StateMachineHandlerNotFound
     * @return StateMachineHandlerInterface
     */
    public function findHandler($stateMachineName)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->getStateMachineName() === $stateMachineName) {
                return $handler;
            }
        }

        throw new StateMachineHandlerNotFound(
            sprintf(
                'State machine handler with name "%s", not found',
                $stateMachineName
            )
        );
    }

}
