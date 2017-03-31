<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Shared\Log\LoggerTrait;
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

    use LoggerTrait;

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
            $requestTransfer = $this->getRequestTransfer($request);
            $this->logRequest($requestTransfer);

            try {
                $responseTransfer = $controller->$action($requestTransfer);
            } catch (\Exception $e) {
                $responseTransfer = new ApiResponseTransfer();
                $responseTransfer->setCode($this->resolveStatusCode($e->getCode()));
                $responseTransfer->setMessage($e->getMessage());
                $responseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
            } catch (\Throwable $e) {
                $responseTransfer = new ApiResponseTransfer();
                $responseTransfer->setCode($this->resolveStatusCode($e->getCode()));
                $responseTransfer->setMessage($e->getMessage());
                $responseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
            }

            $this->logResponse($responseTransfer);

            $responseObject = new Response();
            return $this->getFacade()->transformToResponse($requestTransfer, $responseTransfer, $responseObject);
        };

        $event->setController($apiController);

        return null;
    }

    /**
     * @param int $code
     *
     * @return int
     */
    protected function resolveStatusCode($code)
    {
        if ($code < 200 || $code > 500) {
            return 500;
        }

        return $code;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    protected function getRequestTransfer(Request $request)
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

        if (strpos($request->headers->get('Content-Type'), 'application/json') === 0) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) && isset($data['data']) ? $data['data'] : []);
        }

        $requestData = $request->request->all();
        $requestTransfer->setRequestData($requestData);

        return $requestTransfer;
    }

    /**
     * @param ApiRequestTransfer $requestTransfer
     *
     * @return void
     */
    protected function logRequest(ApiRequestTransfer $requestTransfer)
    {
        $this->getLogger()->info(sprintf(
            'API request [%s %s]: %s',
            $requestTransfer->getRequestType(),
            $requestTransfer->getPath(),
            print_r($requestTransfer, true)
        ));
    }

    /**
     * @param ApiResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function logResponse(ApiResponseTransfer $responseTransfer)
    {
        $this->getLogger()->info(sprintf(
            'API response [code %s]: %s',
            $responseTransfer->getCode(),
            print_r($responseTransfer, true)
        ));
    }

}
