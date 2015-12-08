<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Kernel\Communication\Plugin;

use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerEngine\Zed\FlashMessenger\Business\FlashMessengerFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\ZedRequest\Client\Message;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\TransferServer;
use SprykerFeature\Zed\Kernel\Communication\GatewayControllerListenerInterface;
use SprykerFeature\Zed\ZedRequest\Business\Client\Request;
use SprykerFeature\Zed\ZedRequest\Business\Client\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use SprykerEngine\Zed\FlashMessenger\FlashMessengerConfig;
use SprykerEngine\Zed\Kernel\Locator;

class GatewayControllerListenerPlugin extends AbstractPlugin implements GatewayControllerListenerInterface
{

    /**
     * @var Locator
     */
    private $locator;

    public function __construct()
    {
        $this->locator = Locator::getInstance();
    }

    /**
     * @param FilterControllerEvent $event
     *
     * @return callable
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $currentController = $event->getController();
        $controller = $currentController[0];
        $action = $currentController[1];

        if (!($controller instanceof AbstractGatewayController)) {
            return $currentController;
        }

        $newController = function () use ($controller, $action) {

            FlashMessengerConfig::setMessageTray(FlashMessengerConfig::IN_MEMORY_TRAY);

            $requestTransfer = $this->getRequestTransfer($controller, $action);
            $result = $controller->$action($requestTransfer->getTransfer(), $requestTransfer);
            $response = $this->getResponse($controller, $result);

            return TransferServer::getInstance()
                ->setResponse($response)
                ->send();
        };

        $event->setController($newController);
    }

    /**
     * @param AbstractGatewayController $controller
     * @param string $action
     *
     * @return Request
     * @throw \LogicException
     */
    private function getRequestTransfer(AbstractGatewayController $controller, $action)
    {
        $classReflection = new \ReflectionObject($controller);
        $methodReflection = $classReflection->getMethod($action);
        $parameters = $methodReflection->getParameters();
        $countParameters = count($parameters);

        if ($countParameters > 2 || $countParameters === 2 && end($parameters)->getClass() !== 'SprykerFeature\\Shared\\Library\\Transfer\\Request') {
            throw new \LogicException('Only one transfer object can be received in yves-action');
        }

        /** @var \ReflectionParameter $parameter */
        $parameter = array_shift($parameters);
        if ($parameter) {
            $class = $parameter->getClass();
            if (empty($class)) {
                throw new \LogicException('You need to specify a class for the parameter in the yves-action.');
            }

            $this->validateClassIsTransferObject($class);
        }

        return TransferServer::getInstance()->getRequest();
    }

    /**
     * @param AbstractGatewayController $controller
     * @param $result
     *
     * @return Response
     */
    private function getResponse(AbstractGatewayController $controller, $result)
    {
        $response = new Response();

        if ($result instanceof TransferInterface) {
            $response->setTransfer($result);
        }

        $this->setGatewayControllerMessages($controller, $response);
        $this->setFlashMessengerMessages($response);

        $response->setSuccess($controller->getSuccess());

        return $response;
    }

    /**
     * @param AbstractGatewayController $controller
     * @param Response $response
     *
     * @return void
     */
    private function setGatewayControllerMessages(AbstractGatewayController $controller, Response $response)
    {
        $response->addSuccessMessages($controller->getSuccessMessages());
        $response->addInfoMessages($controller->getInfoMessages());
        $response->addErrorMessages($controller->getErrorMessages());
    }

    /**
     * @param Response $response
     *
     * @return void
     */
    private function setFlashMessengerMessages(Response $response)
    {
        $flashMessengerFacade = $this->createFlashMessengerFacade();

        if ($flashMessengerFacade === null) {
            return;
        }

        $flashMessengerTransfer = $flashMessengerFacade->getStoredMessages();
        if ($flashMessengerTransfer === null) {
            return;
        }

        $response->addErrorMessages(
            $this->createResponseMessages(
                $flashMessengerTransfer->getErrorMessages(),
                $response->getErrorMessages()
            )
        );
        $response->addInfoMessages(
            $this->createResponseMessages(
                $flashMessengerTransfer->getInfoMessages(),
                $response->getInfoMessages()
            )
        );
        $response->addSuccessMessages(
            $this->createResponseMessages(
                $flashMessengerTransfer->getSuccessMessages(),
                $response->getSuccessMessages()
            )
        );
    }

    /**
     * @param \ArrayObject $messages
     * @param array|Message[] $storedMessages
     *
     * @return array|Message[]
     */
    private function createResponseMessages(\ArrayObject $messages, array $storedMessages = [])
    {
        foreach ($messages as $message) {
            $responseMessage = new Message();
            $responseMessage->setMessage($message);
            $storedMessages[] = $responseMessage;
        }

        return $storedMessages;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return bool
     */
    private function validateClassIsTransferObject(\ReflectionClass $class)
    {
        if (substr($class->getName(), 0, 16) === 'Generated\Shared') {
            return true;
        }

        if ($class->getName() === 'SprykerEngine\Shared\Transfer\TransferInterface') {
            return true;
        }

        throw new \LogicException('Only transfer classes are allowed in yves action as parameter');
    }

    /**
     * @return FlashMessengerFacade|null
     */
    private function createFlashMessengerFacade()
    {
        $flashMessenger = $this->locator->flashMessenger();
        if ($flashMessenger !== null) {
            return $flashMessenger->facade();
        }

        return null;
    }

}
