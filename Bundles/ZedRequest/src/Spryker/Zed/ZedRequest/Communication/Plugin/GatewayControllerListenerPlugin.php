<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Communication\Plugin;

use Generated\Shared\Transfer\MessageTransfer;
use LogicException;
use ReflectionObject;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Messenger\MessengerConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Messenger\MessengerConfig;
use Spryker\Zed\ZedRequest\Business\Client\Request;
use Spryker\Zed\ZedRequest\Business\Client\Response;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Spryker\Zed\ZedRequest\Communication\Plugin\TransferObject\TransferServer;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \Spryker\Zed\ZedRequest\Communication\ZedRequestCommunicationFactory getFactory()
 */
class GatewayControllerListenerPlugin extends AbstractPlugin implements GatewayControllerListenerInterface
{

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return callable|null
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

            MessengerConfig::setMessageTray(MessengerConstants::IN_MEMORY_TRAY);

            $requestTransfer = $this->getRequestTransfer($controller, $action);

            $this->setCustomersLocaleIfPresent($requestTransfer);

            $result = $controller->$action($requestTransfer->getTransfer(), $requestTransfer);
            $response = $this->getResponse($controller, $result);

            return TransferServer::getInstance()
                ->setResponse($response)
                ->send();
        };

        $event->setController($newController);
    }

    /**
     * @param \Spryker\Zed\ZedRequest\Business\Client\Request $request
     *
     * @return void
     */
    protected function setCustomersLocaleIfPresent(Request $request)
    {
        $localeTransfer = $this->getLocaleMetaTransfer($request);
        if ($localeTransfer) {
            Store::getInstance()->setCurrentLocale($localeTransfer->getLocaleName());
        }
    }

    /**
     * @param \Spryker\Zed\ZedRequest\Business\Client\Request $request
     *
     * @return null|\Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleMetaTransfer(Request $request)
    {
        $localeTransfer = $request->getMetaTransfer('locale');

        return $localeTransfer;
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController $controller
     * @param string $action
     *
     * @throws \LogicException
     *
     * @return \Spryker\Zed\ZedRequest\Business\Client\Request
     */
    private function getRequestTransfer(AbstractGatewayController $controller, $action)
    {
        $classReflection = new ReflectionObject($controller);
        $methodReflection = $classReflection->getMethod($action);
        $parameters = $methodReflection->getParameters();
        $countParameters = count($parameters);

        if ($countParameters >= 2) {
            throw new LogicException('Only one transfer object can be received in yves-action');
        }

        /** @var \ReflectionParameter $parameter */
        $parameter = array_shift($parameters);
        if ($parameter) {
            $class = $parameter->getClass();
            if (empty($class)) {
                throw new LogicException('You need to specify a class for the parameter in the yves-action.');
            }

            $this->validateClassIsTransferObject($class->getName());
        }

        return TransferServer::getInstance()->getRequest();
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController $controller
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $result
     *
     * @return \Spryker\Zed\ZedRequest\Business\Client\Response
     */
    protected function getResponse(AbstractGatewayController $controller, $result)
    {
        $response = new Response();

        if ($result instanceof TransferInterface) {
            $response->setTransfer($result);
        }

        $this->setGatewayControllerMessages($controller, $response);
        $this->setMessengerMessages($response);

        $response->setSuccess($controller->isSuccess());

        return $response;
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController $controller
     * @param \Spryker\Zed\ZedRequest\Business\Client\Response $response
     *
     * @return void
     */
    protected function setGatewayControllerMessages(AbstractGatewayController $controller, Response $response)
    {
        $response->addSuccessMessages($controller->getSuccessMessages());
        $response->addInfoMessages($controller->getInfoMessages());
        $response->addErrorMessages($controller->getErrorMessages());
    }

    /**
     * @param \Spryker\Zed\ZedRequest\Business\Client\Response $response
     *
     * @return void
     */
    protected function setMessengerMessages(Response $response)
    {
        $messengerFacade = $this->getFactory()->getMessengerFacade();

        $messagesTransfer = $messengerFacade->getStoredMessages();
        if ($messagesTransfer === null) {
            return;
        }

        $response->addErrorMessages(
            $this->createResponseMessages(
                $messagesTransfer->getErrorMessages()
            )
        );
        $response->addInfoMessages(
            $this->createResponseMessages(
                $messagesTransfer->getInfoMessages()
            )
        );
        $response->addSuccessMessages(
            $this->createResponseMessages(
                $messagesTransfer->getSuccessMessages()
            )
        );
    }

    /**
     * @param array $messages
     * @param \Generated\Shared\Transfer\MessageTransfer[] $storedMessages
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    protected function createResponseMessages(array $messages, array $storedMessages = [])
    {
        foreach ($messages as $message) {
            $responseMessage = new MessageTransfer();
            $responseMessage->setValue($message);
            $storedMessages[] = $responseMessage;
        }

        return $storedMessages;
    }

    /**
     * @param string $className
     *
     * @throws \LogicException
     *
     * @return bool
     */
    protected function validateClassIsTransferObject($className)
    {
        if (substr($className, 0, 16) === 'Generated\Shared') {
            return true;
        }

        if ($className === 'Spryker\Shared\Kernel\Transfer\TransferInterface') {
            return true;
        }

        throw new LogicException('Only transfer classes are allowed in yves action as parameter');
    }

}
