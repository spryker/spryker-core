<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\EventListener;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use JsonException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\ApiFacadeInterface;
use Spryker\Zed\Api\Business\Http\HttpConstants;
use Spryker\Zed\Api\Communication\Controller\AbstractApiController;
use Spryker\Zed\Api\Communication\Transformer\TransformerInterface;
use Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Throwable;

class ApiControllerEventListener implements ApiControllerEventListenerInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const REQUEST_URI = 'REQUEST_URI';

    /**
     * @var \Spryker\Zed\Api\Communication\Transformer\TransformerInterface
     */
    protected $transformer;

    /**
     * @var \Spryker\Zed\Api\Business\ApiFacadeInterface
     */
    protected $apiFacade;

    /**
     * @var \Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Api\Communication\Transformer\TransformerInterface $transformer
     * @param \Spryker\Zed\Api\Business\ApiFacadeInterface $apiFacade
     * @param \Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        TransformerInterface $transformer,
        ApiFacadeInterface $apiFacade,
        ApiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->transformer = $transformer;
        $this->apiFacade = $apiFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $controllerEvent
     *
     * @return void
     */
    public function onKernelControllerEvent(ControllerEvent $controllerEvent): void
    {
        $request = $controllerEvent->getRequest();

        if (!$request->server->has(static::REQUEST_URI) || strpos($request->server->get(static::REQUEST_URI), ApiConfig::ROUTE_PREFIX_API_REST) !== 0) {
            return;
        }

        /** @var array $currentController */
        $currentController = $controllerEvent->getController();
        [$controller, $action] = $currentController;

        if (!$controller instanceof AbstractApiController) {
            return;
        }

        $request = $controllerEvent->getRequest();
        try {
            $apiController = function () use ($controller, $action, $request) {
                return $this->executeControllerAction($request, $controller, $action);
            };
        } catch (JsonException $e) {
            $apiController = $this->transformer->transformBadRequest(new ApiResponseTransfer(), new Response(), $e->getMessage());
        }

        $controllerEvent->setController($apiController);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Zed\Api\Communication\Controller\AbstractApiController $controller
     * @param string $action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeControllerAction(Request $request, AbstractApiController $controller, string $action): Response
    {
        $apiRequestTransfer = $this->getApiRequestTransfer($request);
        $this->logRequest($apiRequestTransfer);

        try {
            $responseTransfer = $controller->$action($apiRequestTransfer);
        } catch (Throwable $exception) {
            $responseTransfer = new ApiResponseTransfer();
            $responseTransfer->setCode($this->resolveStatusCode((int)$exception->getCode()));
            $responseTransfer->setMessage($exception->getMessage());
            $responseTransfer->setStackTrace(sprintf(
                '%s (%s, line %d): %s',
                get_class($exception),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getTraceAsString(),
            ));
        }

        $this->logResponse($responseTransfer);

        return $this->transformer->transform($apiRequestTransfer, $responseTransfer, new Response());
    }

    /**
     * @param int $code
     *
     * @return int
     */
    protected function resolveStatusCode(int $code): int
    {
        if ($code < ApiConfig::HTTP_CODE_SUCCESS || $code > ApiConfig::HTTP_CODE_INTERNAL_ERROR) {
            return ApiConfig::HTTP_CODE_INTERNAL_ERROR;
        }

        return $code;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \JsonException
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    protected function getApiRequestTransfer(Request $request): ApiRequestTransfer
    {
        $requestTransfer = new ApiRequestTransfer();

        $requestTransfer->setRequestType($request->getMethod());
        $requestTransfer->setQueryData($request->query->all());
        $requestTransfer->setHeaderData($request->headers->all());

        $serverData = $request->server->all();
        $requestTransfer->setServerData($serverData);
        $requestTransfer->setRequestUri($serverData[static::REQUEST_URI]);

        if (strpos((string)$request->headers->get(HttpConstants::HEADER_CONTENT_TYPE), 'application/json') === 0) {
            $content = $request->getContent();
            if (is_resource($content)) {
                $content = stream_get_contents($content);
                $content = $content ?: '';
            }

            try {
                $data = $this->utilEncodingService->decodeJson($content, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                $this->logRequest($requestTransfer);

                throw $exception;
            }
            $request->request->replace(is_array($data) && isset($data['data']) ? $data['data'] : []);
        }

        return $requestTransfer->setRequestData($request->request->all());
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    protected function logRequest(ApiRequestTransfer $apiRequestTransfer): void
    {
        $filteredApiRequestTransfer = $this->apiFacade->filterApiRequestTransfer($apiRequestTransfer);

        $this->getLogger()->info(sprintf(
            'API request [%s %s]: %s',
            $apiRequestTransfer->getRequestTypeOrFail(),
            $apiRequestTransfer->getRequestUriOrFail(),
            $this->utilEncodingService->encodeJson($filteredApiRequestTransfer->toArray()),
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function logResponse(ApiResponseTransfer $responseTransfer): void
    {
        $responseTransferData = $responseTransfer->toArray();
        unset($responseTransferData['request']);

        $this->getLogger()->info(sprintf(
            'API response [code %s]: %s',
            $responseTransfer->getCodeOrFail(),
            $this->utilEncodingService->encodeJson($responseTransferData),
        ));
    }
}
