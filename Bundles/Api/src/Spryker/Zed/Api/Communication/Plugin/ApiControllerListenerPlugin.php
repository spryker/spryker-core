<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Api\Communication\Controller\AbstractApiController;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacade getFacade()
 */
class ApiControllerListenerPlugin extends AbstractPlugin implements ApiControllerListenerInterface
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

        if (!($controller instanceof AbstractApiController)) {
            return $currentController;
        }

        $request = $event->getRequest();

        $apiController = function () use ($controller, $action, $request) {
            $requestTransfer = $this->getRequestTransfer($controller, $request);

            try {
                $responseTransfer = $controller->$action($requestTransfer);
            } catch (\Exception $e) {
                $responseTransfer = new ApiResponseTransfer();
                $responseTransfer->setCode(500);
                $responseTransfer->setMessage($e->getMessage());
                $responseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
            } catch (\Throwable $e) {
                $responseTransfer = new ApiResponseTransfer();
                $responseTransfer->setCode(500);
                $responseTransfer->setMessage($e->getMessage());
                $responseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
            }

            $responseObject = new Response();
            return $this->getFacade()->transformToResponse($requestTransfer, $responseTransfer, $responseObject);
        };

        $event->setController($apiController);

        return null;
    }

    /**
     * @param \Spryker\Zed\Api\Communication\Controller\AbstractApiController $controller
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    private function getRequestTransfer(AbstractApiController $controller, Request $request)
    {
        $requestTransfer = new ApiRequestTransfer();

        $requestType = $request->getMethod();
        $requestTransfer->setRequestType($requestType);

        $queryData = $request->query->all();
        $requestTransfer->setQueryData($queryData);

        $serverData = $request->server->all();
        $requestTransfer->setServerData($serverData);

        $headerData = $request->headers->all();
        $requestTransfer->setHeaderData($headerData);

        $requestData = $request->request->all();
        $requestTransfer->setRequestData($requestData);

        return $requestTransfer;
    }

    /**
     * @param \Spryker\Zed\Api\Communication\Controller\AbstractApiController $controller
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $result
     *
     * @return \Spryker\Zed\Api\Business\Client\Response
     */
    protected function getResponse(AbstractApiController $controller, $result)
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
     * @param \Spryker\Zed\Api\Communication\Controller\AbstractApiController $controller
     * @param \Spryker\Zed\Api\Business\Client\Response $response
     *
     * @return void
     */
    protected function setGatewayControllerMessages(AbstractApiController $controller, Response $response)
    {
        $response->addSuccessMessages($controller->getSuccessMessages());
        $response->addInfoMessages($controller->getInfoMessages());
        $response->addErrorMessages($controller->getErrorMessages());
    }

    /**
     * @param \Spryker\Zed\Api\Business\Client\Response $response
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

}
