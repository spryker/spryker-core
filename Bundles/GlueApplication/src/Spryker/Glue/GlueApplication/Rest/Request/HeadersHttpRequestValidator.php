<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HeadersHttpRequestValidator implements HeadersHttpRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $config;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    protected $resourceRouteLoader;

    /**
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $config
     * @param \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface $resourceRouteLoader
     */
    public function __construct(
        GlueApplicationConfig $config,
        ResourceRouteLoaderInterface $resourceRouteLoader
    ) {
        $this->config = $config;
        $this->resourceRouteLoader = $resourceRouteLoader;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        $headerData = $request->headers->all();

        $restErrorMessageTransfer = $this->validateAccessControlRequestMethod($request);
        if ($restErrorMessageTransfer) {
            return $restErrorMessageTransfer;
        }

        $restErrorMessageTransfer = $this->validateAccessControlRequestHeader($request);
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
                ->setDetail('Unsupported media type.')
                ->setStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function validateAccessControlRequestHeader(Request $request): ?RestErrorMessageTransfer
    {
        $requestedHeaders = strtolower((string)$request->headers->get(RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_HEADER));
        if (!$requestedHeaders) {
            return null;
        }

        $requestedHeaders = explode(',', $requestedHeaders);

        $allowedHeaders = $this->config->getCorsAllowedHeaders();

        foreach ($requestedHeaders as $requestedHeader) {
            if (in_array($requestedHeader, $allowedHeaders, false)) {
                continue;
            }

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
    protected function validateAccessControlRequestMethod(Request $request): ?RestErrorMessageTransfer
    {
        $requestedMethod = strtoupper((string)$request->headers->get(RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_METHOD));

        if (!$requestedMethod) {
            return null;
        }

        $availableMethods = $this->resourceRouteLoader->getAvailableMethods(
            $request->attributes->get(RequestConstantsInterface::ATTRIBUTE_TYPE),
            $request->attributes->get(RequestConstantsInterface::ATTRIBUTE_ALL_RESOURCES),
            $request
        );

        if (!in_array($requestedMethod, $availableMethods, false)) {
            return (new RestErrorMessageTransfer())
                ->setDetail('Not allowed.')
                ->setStatus(Response::HTTP_FORBIDDEN);
        }

        return null;
    }
}
