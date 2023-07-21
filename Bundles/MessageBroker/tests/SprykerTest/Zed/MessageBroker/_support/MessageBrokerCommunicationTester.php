<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker;

use Codeception\Actor;
use Codeception\Stub;
use Codeception\Stub\Expected;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MessageBroker\Business\MessageBrokerFacade;
use Spryker\Zed\MessageBroker\Communication\Plugin\Console\MessageBrokerDebugConsole;
use Spryker\Zed\MessageBroker\Communication\Plugin\Console\MessageBrokerWorkerConsole;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\ValidationMiddlewarePlugin;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\MessageBroker\PHPMD)
 */
class MessageBrokerCommunicationTester extends Actor
{
    use _generated\MessageBrokerCommunicationTesterActions;

    /**
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getWorkerConsoleCommandTester(): CommandTester
    {
        $this->mockWorker();
        $facade = $this->getFacade();

        $command = new MessageBrokerWorkerConsole();
        $command->setFacade($facade);

        return $this->getConsoleTester($command);
    }

    /**
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    public function getDebugConsoleCommandTester(): CommandTester
    {
        $facade = $this->getFacade();

        $command = new MessageBrokerDebugConsole();
        $command->setFacade($facade);

        return $this->getConsoleTester($command);
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\ValidationMiddlewarePlugin
     */
    public function createValidationMiddlewarePluginThatCanHandleAMessage(): ValidationMiddlewarePlugin
    {
        $messageBrokerFacadeMock = Stub::make(MessageBrokerFacade::class, [
            'canHandleMessage' => true,
        ]);

        $validationMiddlewarePlugin = new ValidationMiddlewarePlugin();
        $validationMiddlewarePlugin->setFacade($messageBrokerFacadeMock);

        return $validationMiddlewarePlugin;
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\ValidationMiddlewarePlugin
     */
    public function createValidationMiddlewarePluginThatCanNotHandleAMessage(): ValidationMiddlewarePlugin
    {
        $messageBrokerFacadeMock = Stub::make(MessageBrokerFacade::class, [
            'canHandleMessage' => false,
        ]);

        $validationMiddlewarePlugin = new ValidationMiddlewarePlugin();
        $validationMiddlewarePlugin->setFacade($messageBrokerFacadeMock);

        return $validationMiddlewarePlugin;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Middleware\MiddlewareInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createMiddlewareInterfaceMock(Envelope $envelope): MiddlewareInterface|MockObject
    {
        return Stub::makeEmpty(MiddlewareInterface::class, [
            'handle' => $envelope,
        ]);
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Middleware\StackInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createStackMockWithOnceCalledNextMethod(Envelope $envelope): StackInterface|MockObject
    {
        $stackMock = Stub::makeEmpty(StackInterface::class);

        $middlewareInterfaceMock = $this->createMiddlewareInterfaceMock($envelope);

        $stackMock->expects(Expected::once()->getMatcher())->method('next')->willReturn($middlewareInterfaceMock);

        return $stackMock;
    }

    /**
     * @return \Symfony\Component\Messenger\Middleware\StackInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createStackMockWithNeverCalledNextMethod(): StackInterface|MockObject
    {
        $stackMock = Stub::makeEmpty(StackInterface::class);

        $stackMock->expects(Expected::never()->getMatcher())->method('next');

        return $stackMock;
    }
}
