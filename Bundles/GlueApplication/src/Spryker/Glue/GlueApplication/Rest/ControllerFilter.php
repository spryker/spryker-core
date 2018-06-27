<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

use Exception;
use Spryker\Glue\GlueApplication\Controller\AbstractRestController;
use Spryker\Glue\GlueApplication\Controller\ErrorController;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RequestFormatterInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatterInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseHeadersInterface;
use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;

class ControllerFilter implements ControllerFilterInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\RequestFormatterInterface
     */
    protected $requestFormatter;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatterInterface
     */
    protected $responseFormatter;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Response\ResponseHeadersInterface
     */
    protected $responseHeaders;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface
     */
    protected $httpRequestValidator;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface
     */
    protected $restRequestValidator;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ControllerCallbacksInterface
     */
    protected $controllerCallbacks;

    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $applicationConfig;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestFormatterInterface $requestFormatter
     * @param \Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatterInterface $responseFormatter
     * @param \Spryker\Glue\GlueApplication\Rest\Response\ResponseHeadersInterface $responseHeaders
     * @param \Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface $httpRequestValidator
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface $restRequestValidator
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\GlueApplication\Rest\ControllerCallbacksInterface $controllerCallbacks
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $applicationConfig
     */
    public function __construct(
        RequestFormatterInterface $requestFormatter,
        ResponseFormatterInterface $responseFormatter,
        ResponseHeadersInterface $responseHeaders,
        HttpRequestValidatorInterface $httpRequestValidator,
        RestRequestValidatorInterface $restRequestValidator,
        RestResourceBuilderInterface $restResourceBuilder,
        ControllerCallbacksInterface $controllerCallbacks,
        GlueApplicationConfig $applicationConfig
    ) {
        $this->requestFormatter = $requestFormatter;
        $this->responseFormatter = $responseFormatter;
        $this->responseHeaders = $responseHeaders;
        $this->httpRequestValidator = $httpRequestValidator;
        $this->restRequestValidator = $restRequestValidator;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->controllerCallbacks = $controllerCallbacks;
        $this->applicationConfig = $applicationConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Controller\AbstractRestController $controller
     * @param string $action
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filter(AbstractRestController $controller, string $action, HttpRequest $httpRequest): Response
    {
        try {
            $restErrorMessageTransfer = $this->httpRequestValidator->validate($httpRequest);
            if ($restErrorMessageTransfer) {
                return new Response($restErrorMessageTransfer->getDetail(), $restErrorMessageTransfer->getStatus());
            }

            $restRequest = $this->requestFormatter->formatRequest($httpRequest);

            $restErrorMessageTransfer = $this->restRequestValidator->validate($httpRequest, $restRequest);

            if (!$restErrorMessageTransfer) {
                $restResponse = $this->executeAction($controller, $action, $restRequest);
            } else {
                $restResponse = $this->restResourceBuilder
                    ->createRestResponse()
                    ->addError($restErrorMessageTransfer);
            }

            $httpResponse = $this->responseFormatter->format($restResponse, $restRequest);

            return $this->responseHeaders->addHeaders($httpResponse, $restResponse, $restRequest);
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Controller\AbstractRestController $controller
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function processResource(
        AbstractRestController $controller,
        string $action,
        RestRequestInterface $restRequest
    ): RestResponseInterface {

        $controller->setRestRequest($restRequest);

        return $controller->$action($restRequest->getResource()->getAttributes());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Controller\AbstractRestController $controller
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function executeAction(
        AbstractRestController $controller,
        string $action,
        RestRequestInterface $restRequest
    ): RestResponseInterface {

        $this->controllerCallbacks->beforeAction($action, $restRequest);

        if ($controller instanceof ErrorController) {
            $restResponse = $controller->$action();
        } else {
            $restResponse = $this->processResource($controller, $action, $restRequest);
        }

        $this->controllerCallbacks->afterAction($action, $restRequest, $restResponse);

        return $restResponse;
    }

    /**
     * @param \Exception $exception
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleException(Exception $exception): Response
    {
        if ($this->applicationConfig->getIsRestDebug()) {
            throw $exception;
        }

        $this->logException($exception);

        return new Response(
            Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * @param \Exception $exception
     *
     * @return void
     */
    protected function logException(Exception $exception): void
    {
        if (!$this->getLogger()) {
            return;
        }

        $this->getLogger()->error($exception->getMessage(), ['trace' => $exception->getTraceAsString()]);
    }
}
