<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedRequest\Communication\Plugin;

use Codeception\Test\Unit;
use LogicException;
use ReflectionClass;
use ReflectionObject;
use Spryker\Shared\Kernel\AbstractLocatorLocator;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\ZedRequest\Business\Model\Repeater;
use Spryker\Zed\ZedRequest\Communication\Plugin\GatewayControllerListenerPlugin;
use Spryker\Zed\ZedRequest\Communication\Plugin\TransferObject\TransferServer as CoreTransferServer;
use SprykerTest\Zed\ZedRequest\Communication\Plugin\Fixture\FilterControllerEvent;
use SprykerTest\Zed\ZedRequest\Communication\Plugin\Fixture\GatewayController;
use SprykerTest\Zed\ZedRequest\Communication\Plugin\Fixture\NotGatewayController;
use SprykerTest\Zed\ZedRequest\Communication\Plugin\Fixture\Request;
use SprykerTest\Zed\ZedRequest\Communication\Plugin\Fixture\TransferServer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ZedRequest
 * @group Communication
 * @group Plugin
 * @group GatewayControllerListenerPluginTest
 * Add your own group annotations below this line
 */
class GatewayControllerListenerPluginTest extends Unit
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->unsetLocator();
    }

    /**
     * We need to unset the Locator instance because we are using the Locator for Yves and for Zed
     * When it first get instantiated by Yves it wont have the proper Proxies configured
     *
     * @return void
     */
    protected function unsetLocator(): void
    {
        $reflectionClass = new ReflectionClass(AbstractLocatorLocator::class);
        $reflectedProperty = $reflectionClass->getProperty('instance');
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue(null);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->resetTransferServer();
    }

    /**
     * @return void
     */
    public function testWhenControllerIsGatewayControllerPluginMustReturnInstanceOfClosure(): void
    {
        $eventMock = new FilterControllerEvent();
        $controller = new GatewayController();
        $action = 'goodAction';
        $eventMock->setController([$controller, $action]);

        $controllerListenerPlugin = new GatewayControllerListenerPlugin();
        $controllerListenerPlugin->onKernelController($eventMock);

        $controllerCallable = $eventMock->getController();
        $this->assertTrue(is_callable($controllerCallable));
        $this->assertInstanceOf('\Closure', $controllerCallable);
    }

    /**
     * @return void
     */
    public function testWhenControllerIsNotAGatewayControllerPluginMustReturnPassedCallable(): void
    {
        $action = 'badAction';
        $eventMock = new FilterControllerEvent();
        $controller = new NotGatewayController();
        $eventMock->setController([$controller, $action]);

        $controllerListenerPlugin = new GatewayControllerListenerPlugin();
        $controllerListenerPlugin->onKernelController($eventMock);

        $controllerCallable = $eventMock->getController();
        $this->assertTrue(is_callable($controllerCallable));
        $this->assertNotInstanceOf('\Closure', $controllerCallable);
    }

    /**
     * @return void
     */
    public function testIfTwoTransferParameterGivenPluginMustThrowException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Only one transfer object can be received in yves-action');

        $action = 'twoTransferParametersAction';
        $controllerCallable = $this->executeMockedListenerTest($action);
        call_user_func($controllerCallable);
    }

    /**
     * @return void
     */
    public function testIfTooManyTransferParameterGivenPluginMustThrowException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Only one transfer object can be received in yves-action');

        $action = 'tooManyParametersAction';
        $controllerCallable = $this->executeMockedListenerTest($action);
        call_user_func($controllerCallable);
    }

    /**
     * @return void
     */
    public function testIfPassedParameterIsNotAClassPluginMustThrowException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('You need to specify a class for the parameter in the yves-action.');

        $action = 'noClassParameterAction';
        $controllerCallable = $this->executeMockedListenerTest($action);
        call_user_func($controllerCallable);
    }

    /**
     * @return void
     */
    public function testWhenObjectIsNotTransferClassPluginMustThrowException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Only transfer classes are allowed in yves action as parameter');

        $transfer = new class () implements TransferInterface
        {
            /**
             * @param bool $isRecursive
             *
             * @return array
             */
            public function toArray($isRecursive = true)
            {
                return [];
            }

            /**
             * @param bool $isRecursive
             *
             * @return array
             */
            public function modifiedToArray($isRecursive = true)
            {
                return [];
            }

            /**
             * @param array $values
             * @param bool $fuzzyMatch
             *
             * @return $this
             */
            public function fromArray(array $values, $fuzzyMatch = false)
            {
                return $this;
            }

            /**
             * @param string $propertyName
             *
             * @return bool
             */
            public function isPropertyModified($propertyName)
            {
                return (bool)$propertyName;
            }
        };
        $controllerCallable = $this->executeMockedListenerTest('notTransferAction', $transfer);
        call_user_func($controllerCallable);
    }

    /**
     * @return void
     */
    public function testWhenControllerIsGatewayControllerAndOnlyOneTransferObjectIsGivenActionMustReturnResponse(): void
    {
        $transfer = $this->getTransferMock();
        $controllerCallable = $this->executeMockedListenerTest('goodAction', $transfer);

        $response = call_user_func($controllerCallable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * @return void
     */
    public function testTransformMessagesFromController(): void
    {
        $action = 'transformMessageAction';

        $transfer = $this->getTransferMock();
        $controllerCallable = $this->executeMockedListenerTest($action, $transfer);

        $response = call_user_func($controllerCallable);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseContent = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('infoMessages', $responseContent);
        $this->assertArrayHasKey('errorMessages', $responseContent);
        $this->assertArrayHasKey('successMessages', $responseContent);
        $this->assertArrayHasKey('success', $responseContent);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ZedRequest\Business\Model\Repeater
     */
    private function createRepeaterMock(): Repeater
    {
        return $this->getMockBuilder(Repeater::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transferObject
     *
     * @return void
     */
    private function initTransferServer(TransferInterface $transferObject): void
    {
        $oldTransferServer = CoreTransferServer::getInstance();
        $this->resetSingleton($oldTransferServer);

        $request = new Request();
        $request->setFixtureTransfer($transferObject);
        TransferServer::getInstance()->setFixtureRequest($request);
    }

    /**
     * @return void
     */
    private function resetTransferServer(): void
    {
        $fixtureServer = TransferServer::getInstance();
        $this->resetSingleton($fixtureServer);
        CoreTransferServer::getInstance(
            $this->createRepeaterMock()
        );
    }

    /**
     * @param \Spryker\Zed\ZedRequest\Communication\Plugin\TransferObject\TransferServer $oldTransferServer
     *
     * @return void
     */
    private function resetSingleton(CoreTransferServer $oldTransferServer): void
    {
        $refObject = new ReflectionObject($oldTransferServer);
        $refProperty = $refObject->getProperty('instance');
        $refProperty->setAccessible(true);
        $refProperty->setValue(null);
    }

    /**
     * @param string $action
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $transfer
     *
     * @return callable
     */
    private function executeMockedListenerTest(string $action, ?TransferInterface $transfer = null): callable
    {
        $eventMock = new FilterControllerEvent();
        $controller = new GatewayController();
        $eventMock->setController([$controller, $action]);

        $controllerListenerPlugin = new GatewayControllerListenerPlugin();

        if (!$transfer) {
            $transfer = $this->getTransferMock();
        }

        $this->initTransferServer($transfer);

        $controllerListenerPlugin->onKernelController($eventMock);
        $controllerCallable = $eventMock->getController();

        return $controllerCallable;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    private function getTransferMock(): TransferInterface
    {
        $transfer = $this->getMockBuilder(TransferInterface::class)->getMock();

        return $transfer;
    }
}
