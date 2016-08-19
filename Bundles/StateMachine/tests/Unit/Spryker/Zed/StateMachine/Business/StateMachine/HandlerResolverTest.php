<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\StateMachine\Business\StateMachine;

use Spryker\Zed\StateMachine\Business\Exception\StateMachineHandlerNotFound;
use Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolver;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Unit\Spryker\Zed\StateMachine\Mocks\StateMachineMocks;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group StateMachine
 * @group HandlerResolverTest
 */
class HandlerResolverTest extends StateMachineMocks
{

    const TEST_HANDLER_NAME = 'testing state machine name';

    /**
     * @return void
     */
    public function testHandlerResolverShouldReturnInstanceOfHandlerWhenCorrectNameGiven()
    {
        $handlerResolver = $this->createHandlerResolver()->get(self::TEST_HANDLER_NAME);

        $this->assertInstanceOf(StateMachineHandlerInterface::class, $handlerResolver);
    }

    /**
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineHandlerNotFound
     *
     * @return void
     *
     */
    public function testHandlerResolverWhenRequestedNonExistantShouldThrowException()
    {
        $this->expectException(StateMachineHandlerNotFound::class);

        $this->createHandlerResolver()->get('no existing state machine');
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolver
     */
    protected function createHandlerResolver()
    {
        $stateMachineHandlerMock = $this->createStateMachineHandlerMock();
        $stateMachineHandlerMock->method('getStateMachineName')->willReturn(self::TEST_HANDLER_NAME);

        return new HandlerResolver([$stateMachineHandlerMock]);
    }

}
