<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpRequestValidator implements HttpRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[]
     */
    protected $requestValidatorPlugins = [];

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    protected $resourceRouteLoader;

    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[] $requestValidatorPlugins
     * @param \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface $resourceRouteLoader
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $config
     */
    public function __construct(
        array $requestValidatorPlugins,
        ResourceRouteLoaderInterface $resourceRouteLoader,
        GlueApplicationConfig $config
    ) {
        $this->requestValidatorPlugins = $requestValidatorPlugins;
        $this->resourceRouteLoader = $resourceRouteLoader;
        $this->config = $config;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        $restErrorMessageTransfer = $this->validateRequiredHeaders($request);
        if (!$restErrorMessageTransfer) {
            $restErrorMessageTransfer = $this->executeRequestValidationPlugins($request);
        }

        return $restErrorMessageTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function validateRequiredHeaders(Request $request): ?RestErrorMessageTransfer
    {
        $headerData = $request->headers->all();

        $restErrorMessageTransfer = $this->validateAccessControlRequestMethod($headerData, $request);
        if ($restErrorMessageTransfer) {
            return $restErrorMessageTransfer;
        }

        $restErrorMessageTransfer = $this->validateAccessControllRequestHeader($headerData);
        if ($restErrorMessageTransfer) {
            return $restErrorMessageTransfer;
        }

        if (!isset($headerData[RequestConstantsInterface::HEADER_ACCEPT])) {
            return (new RestErrorMessageTransfer())
                ->setDetail('Not acceptable.')
                ->setStatus(Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!isset($headerData[RequestConstantsInterface::HEADER_CONTENT_TYPE])) {
            return (new RestErrorMessageTransfer())
                ->setDetail('Unsuported media type.')
                ->setStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        return null;
    }

    /**
     * @param array $headerData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function validateAccessControlRequestMethod(array $headerData, Request $request): ?RestErrorMessageTransfer
    {
        if (!isset($headerData[RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_METHOD])) {
            return null;
        }

        $requestedMethod = strtoupper((string)$headerData[RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_METHOD]);

        $availableMethods = $this->resourceRouteLoader->getAvailableMethods(
            $request->attributes->get(RequestConstantsInterface::ATTRIBUTE_TYPE),
            $request
        );

        if (!\in_array($requestedMethod, $availableMethods, true)) {
            return (new RestErrorMessageTransfer())
                ->setDetail('Not allowed.')
                ->setStatus(Response::HTTP_FORBIDDEN);
        }

        return null;
    }

    /**
     * @param array $headerData
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function validateAccessControllRequestHeader(array $headerData): ?RestErrorMessageTransfer
    {
        if (!isset($headerData[RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_HEADER])) {
            return null;
        }

        $requestedHeaders = explode(
            ',',
            strtolower((string)$headerData[RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_HEADER])
        );

        $allowedHeaders = $this->config->getCorsAllowedHeaders();

        if (!\in_array($requestedHeaders, $allowedHeaders, true)) {
            return (new RestErrorMessageTransfer())
                ->setDetail('Not allowed.')
                ->setStatus(Response::HTTP_FORBIDDEN);
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function executeRequestValidationPlugins(Request $request): ?RestErrorMessageTransfer
    {
        foreach ($this->requestValidatorPlugins as $requestValidatorPlugin) {
            $restErrorMessageTransfer = $requestValidatorPlugin->validate($request);
            if (!$restErrorMessageTransfer) {
                continue;
            }

            return $restErrorMessageTransfer;
        }
        return null;
    }
}
