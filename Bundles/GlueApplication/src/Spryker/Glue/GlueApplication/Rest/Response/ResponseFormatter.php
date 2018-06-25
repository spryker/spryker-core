<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\Encoder\EncoderInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;

class ResponseFormatter implements ResponseFormatterInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface $encoderMatcher
     */
    protected $encoderMatcher;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface
     */
    protected $restResponseBuilder;

    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseDataPluginInterface[]
     */
    protected $formatDataResponsePlugins = [];

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface $encoderMatcher
     * @param \Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface $responseBuilder
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseDataPluginInterface[] $formatDataResponsePlugins
     */
    public function __construct(
        EncoderMatcherInterface $encoderMatcher,
        ResponseBuilderInterface $responseBuilder,
        array $formatDataResponsePlugins = []
    ) {
        $this->encoderMatcher = $encoderMatcher;
        $this->restResponseBuilder = $responseBuilder;
        $this->formatDataResponsePlugins = $formatDataResponsePlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function format(RestResponseInterface $restResponse, RestRequestInterface $restRequest): Response
    {
        $encoder = $this->encoderMatcher->match($restRequest->getMetadata());

        if (!$encoder) {
            return new Response(
                Response::$statusTexts[Response::HTTP_UNSUPPORTED_MEDIA_TYPE],
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE
            );
        }

        if (count($restResponse->getErrors()) > 0) {
            return $this->getErrorResponse($restResponse, $encoder);
        }

        $responseData = $this->restResponseBuilder->buildResponse($restResponse, $restRequest);
        $responseData = $this->executeFormatDataResponsePlugins($restRequest, $responseData);

        return new Response(
            $encoder->encode($responseData),
            $this->getStatusCode($restRequest->getMetadata())
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array $preparedResponseData
     *
     * @return array
     */
    protected function executeFormatDataResponsePlugins(RestRequestInterface $restRequest, array $preparedResponseData): array
    {
        foreach ($this->formatDataResponsePlugins as $responseDataPlugin) {
            $preparedResponseData = $responseDataPlugin->format($restRequest, $preparedResponseData);
        }

        return $preparedResponseData;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Spryker\Glue\GlueApplication\Rest\Serialize\Encoder\EncoderInterface $encoder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getErrorResponse(RestResponseInterface $restResponse, EncoderInterface $encoder): Response
    {
        $response = [];
        $responseStatus = Response::HTTP_BAD_REQUEST;
        foreach ($restResponse->getErrors() as $responseErrorTransfer) {
            if (!$responseErrorTransfer->getStatus()) {
                $responseErrorTransfer->setStatus($responseStatus);
            }

            $responseStatus = $responseErrorTransfer->getStatus();

            $response[RestResponseInterface::RESPONSE_ERRORS][] = $responseErrorTransfer->toArray();
        }
        return new Response(
            $encoder->encode($response),
            $responseStatus
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return int
     */
    protected function getStatusCode(MetadataInterface $metadata): int
    {
        switch ($metadata->getMethod()) {
            case HttpRequest::METHOD_GET:
            case HttpRequest::METHOD_PATCH:
                return Response::HTTP_OK;
            case HttpRequest::METHOD_POST:
                return Response::HTTP_CREATED;
            case HttpRequest::METHOD_DELETE:
                return Response::HTTP_NO_CONTENT;
        }

        return Response::HTTP_OK;
    }
}
