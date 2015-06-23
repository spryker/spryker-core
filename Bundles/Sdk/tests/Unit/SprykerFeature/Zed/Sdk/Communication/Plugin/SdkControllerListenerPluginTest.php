<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Sdk\Communication\Plugin;

use Generated\Shared\Transfer\GoodTransfer;
use SprykerEngine\Shared\Transfer\AbstractTransfer;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Foo\Sdk\NotTransferTransferObject;
use SprykerFeature\Shared\Library\Communication\Response;
use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\Repeater;
use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\TransferServer as CoreTransferServer;
use SprykerFeature\Zed\Sdk\Communication\Plugin\SdkControllerListenerPlugin;
use Symfony\Component\HttpFoundation\JsonResponse;
use Unit\SprykerFeature\Zed\Sdk\Communication\Plugin\Fixture\FooTransfer;
use Unit\SprykerFeature\Zed\Sdk\Communication\Plugin\Fixture\NotSdkController;
use Unit\SprykerFeature\Zed\Sdk\Communication\Plugin\Fixture\Request;
use Unit\SprykerFeature\Zed\Sdk\Communication\Plugin\Fixture\SdkController;
use Unit\SprykerFeature\Zed\Sdk\Communication\Plugin\Fixture\TransferServer;

/**
 * @group Zed
 * @group Communication
 * @group Sdk
 * @group SdkListener
 */
class SdkControllerListenerPluginTest extends \PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        $this->resetTransferServer();
    }


    public function testIsSdkController()
    {
        $eventMock = new Fixture\FilterControllerEvent();
        $sdkController = new SdkController();
        $action = 'fooAction';
        $eventMock->setController([$sdkController, $action]);

        $sdkControllerListenerPlugin = new SdkControllerListenerPlugin(
            $this->createFactoryMock(),
            $this->createLocatorMock()
        );
        $sdkControllerListenerPlugin->onKernelController($eventMock);

        $controllerCallable = $eventMock->getController();
        $this->assertTrue(is_callable($controllerCallable));
        $this->assertTrue(($controllerCallable instanceof \Closure));
    }

    public function testIsNotSdkController()
    {
        $action = 'fooAction';
        $eventMock = new Fixture\FilterControllerEvent();
        $sdkController = new NotSdkController();
        $eventMock->setController([$sdkController, $action]);

        $sdkControllerListenerPlugin = new SdkControllerListenerPlugin(
            $this->createFactoryMock(),
            $this->createLocatorMock()
        );
        $sdkControllerListenerPlugin->onKernelController($eventMock);

        $controllerCallable = $eventMock->getController();
        $this->assertTrue(is_callable($controllerCallable));
        $this->assertFalse(($controllerCallable instanceof \Closure));
    }

    public function testOneTransferParameter()
    {
        $action = 'barAction';
        $controllerCallable = $this->executeMockedListenerTest($action);

        $this->assertTrue(is_callable($controllerCallable));
        $this->assertTrue(($controllerCallable instanceof \Closure));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Only one transfer object can be received in yves-action
     */
    public function testTwoTransferParameter()
    {
        $action = 'twoTransferParametersAction';
        $controllerCallable = $this->executeMockedListenerTest($action);
        call_user_func($controllerCallable);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Only one transfer object can be received in yves-action
     */
    public function testTooManyTransferParameter()
    {
        $action = 'tooManyParametersAction';
        $controllerCallable = $this->executeMockedListenerTest($action);
        call_user_func($controllerCallable);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage You need to specify a class for the parameter in the yves-action.
     */
    public function testMissingTypehint()
    {
        $action = 'withoutTypehintAction';
        $controllerCallable = $this->executeMockedListenerTest($action);
        call_user_func($controllerCallable);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Only transfer classes are allowed in yves action as parameter
     */
    public function testIsNotSharedTransferClass()
    {
        $action = 'notSharedTransferNamespaceAction';
        $controllerCallable = $this->executeMockedListenerTest($action);
        call_user_func($controllerCallable);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Only transfer classes are allowed in yves action as parameter
     */
    public function testIsNotTransferTransferClass()
    {
        //bypass autoloader
        require_once __DIR__ . '/Fixture/NotTransferTransferObject.php';
        $transfer = new NotTransferTransferObject($this->createLocatorMock());
        $controllerCallable = $this->executeMockedListenerTest('notTransferTransferNamespaceAction', $transfer);
        call_user_func($controllerCallable);
    }

    public function testIsSharedTransferClass()
    {
        //bypass autoloader
        require_once __DIR__ . '/Fixture/GoodTransfer.php';
        $transfer = new GoodTransfer($this->createLocatorMock());
        $controllerCallable = $this->executeMockedListenerTest('goodAction', $transfer);
        /** @var Response $response */
        $response = call_user_func($controllerCallable);
        $this->assertInstanceOf('Symfony\\Component\\HttpFoundation\\JsonResponse', $response);
    }

    public function testTransformMessagesFromController()
    {
        $action = 'transformMessageAction';
        //bypass autoloader
        require_once __DIR__ . '/Fixture/GoodTransfer.php';
        $transfer = new GoodTransfer($this->createLocatorMock());
        $controllerCallable = $this->executeMockedListenerTest($action, $transfer);
        /** @var JsonResponse $response */
        $response = call_user_func($controllerCallable);
        $this->assertInstanceOf('Symfony\\Component\\HttpFoundation\\JsonResponse', $response);

        $responseContent = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('messages', $responseContent);
        $this->assertArrayHasKey('errorMessages', $responseContent);
        $this->assertArrayHasKey('success', $responseContent);
        $this->assertEquals([['data' => ['key' => 'value'], 'message' => 'message']], $responseContent['messages']);
        $this->assertEquals([['data' => ['errorKey' => 'errorValue'], 'message' => 'error']], $responseContent['errorMessages']);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Factory
     */
    private function createFactoryMock()
    {
        return $this->getMockBuilder('SprykerEngine\Zed\Kernel\Communication\Factory')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Locator
     */
    private function createLocatorMock()
    {
        return $this->getMockBuilder('SprykerEngine\Zed\Kernel\Locator')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Repeater
     */
    private function createRepeaterMock()
    {
        return $this->getMockBuilder('SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\Repeater')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function initTransferServer(TransferInterface $transferObject)
    {
        $oldTransferServer = CoreTransferServer::getInstance();
        $this->resetSingleton($oldTransferServer);

        $request = new Request();
        $request->setTransfer($transferObject);
        TransferServer::getInstance()->setFixtureRequest($request);
    }

    private function resetTransferServer()
    {
        $fixtureServer = TransferServer::getInstance();
        $this->resetSingleton($fixtureServer);
        CoreTransferServer::getInstance(
            $this->createRepeaterMock()
        );
    }

    /**
     * @param $oldTransferServer
     *
     */
    private function resetSingleton($oldTransferServer)
    {
        $refObject = new \ReflectionObject($oldTransferServer);
        $refProperty = $refObject->getProperty('instance');
        $refProperty->setAccessible(true);
        $refProperty->setValue(null);
    }

    /**
     * @param string $action
     * @param AbstractTransfer $transfer
     *
     * @return callable
     */
    private function executeMockedListenerTest($action, $transfer = null)
    {
        $eventMock = new Fixture\FilterControllerEvent();
        $sdkController = new SdkController();
        $eventMock->setController([$sdkController, $action]);

        $sdkControllerListenerPlugin = new SdkControllerListenerPlugin(
            $this->createFactoryMock(),
            $this->createLocatorMock()
        );

        if (!$transfer) {
            $transfer = new FooTransfer($this->createLocatorMock());
        }

        $this->initTransferServer($transfer);

        $sdkControllerListenerPlugin->onKernelController($eventMock);
        $controllerCallable = $eventMock->getController();

        return $controllerCallable;
    }
}
