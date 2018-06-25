<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Response;

class ResponseHeaders implements ResponseHeadersInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface[]
     */
    protected $formatResponseHeadersPlugins = [];

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    protected $contentTypeResolver;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface[] $formatResponseHeadersPlugins
     * @param \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface $contentTypeResolver
     */
    public function __construct(
        array $formatResponseHeadersPlugins,
        ContentTypeResolverInterface $contentTypeResolver
    ) {
        $this->formatResponseHeadersPlugins = $formatResponseHeadersPlugins;
        $this->contentTypeResolver = $contentTypeResolver;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $httpResponse
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addHeaders(
        Response $httpResponse,
        RestResponseInterface $restResponse,
        RestRequestInterface $restRequest
    ): Response {

        $this->contentTypeResolver->addResponseHeaders($restRequest, $httpResponse);

        $httpResponse->headers->set(
            RequestConstantsInterface::HEADER_CONTENT_LANGUAGE,
            $restRequest->getMetadata()->getLocale()
        );

        $httpResponse = $this->executeResponseHeaderPlugins($httpResponse, $restResponse, $restRequest);

        $this->setHeadersFromRestResponse($httpResponse, $restResponse);

        return $httpResponse;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $httpResponse
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeResponseHeaderPlugins(
        Response $httpResponse,
        RestResponseInterface $restResponse,
        RestRequestInterface $restRequest
    ): Response {

        foreach ($this->formatResponseHeadersPlugins as $formatResponseHeadersPlugin) {
            $httpResponse = $formatResponseHeadersPlugin->format($httpResponse, $restResponse, $restRequest);
        }

        return $httpResponse;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $httpResponse
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return void
     */
    protected function setHeadersFromRestResponse(Response $httpResponse, RestResponseInterface $restResponse): void
    {
        foreach ($restResponse->getHeaders() as $key => $value) {
            $httpResponse->headers->set($key, $value);
        }
    }
}
