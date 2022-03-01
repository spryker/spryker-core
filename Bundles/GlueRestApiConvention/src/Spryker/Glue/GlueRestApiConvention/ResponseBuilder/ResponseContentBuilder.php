<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\ResponseBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;

class ResponseContentBuilder implements ResponseContentBuilderInterface
{
    /**
     * @var array<string, array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface>>
     */
    protected array $responseEncoders = [];

    /**
     * @var \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig
     */
    protected GlueRestApiConventionConfig $glueRestApiConventionConfig;

    /**
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface> $responseEncoderPlugins
     * @param \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig $glueRestApiConventionConfig
     */
    public function __construct(
        array $responseEncoderPlugins,
        GlueRestApiConventionConfig $glueRestApiConventionConfig
    ) {
        array_map(function (ResponseEncoderPluginInterface $responseEncoderPlugin): void {
            $this->addResponseEncoderPlugin($responseEncoderPlugin);
        }, $responseEncoderPlugins);
        $this->glueRestApiConventionConfig = $glueRestApiConventionConfig;
    }

    /**
     * @param \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface $responseEncoder
     *
     * @return $this
     */
    public function addResponseEncoderPlugin(ResponseEncoderPluginInterface $responseEncoder)
    {
        foreach ($responseEncoder->getAcceptedFormats() as $acceptedFormat) {
            if (!isset($this->responseEncoders[$acceptedFormat])) {
                $this->responseEncoders[$acceptedFormat] = [];
            }

            $this->responseEncoders[$acceptedFormat][] = $responseEncoder;
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function buildResponse(
        GlueResponseTransfer $glueResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        if (!$glueResponseTransfer->getHttpStatus()) {
            $glueResponseTransfer->setHttpStatus($this->getStatusCode($glueRequestTransfer));
        }

        if ($glueResponseTransfer->getContent()) {
            return $glueResponseTransfer;
        }

        if (!array_key_exists($glueRequestTransfer->getAcceptedFormatOrFail(), $this->responseEncoders)) {
            $glueRequestTransfer->setAcceptedFormat(
                $this->glueRestApiConventionConfig->getDefaultFormat(),
            );
        }

        $data = $this->expandData($glueResponseTransfer);

        return $this->formatResponse($glueRequestTransfer->getAcceptedFormatOrFail(), $data, $glueResponseTransfer, $glueRequestTransfer);
    }

    /**
     * @param string $format
     * @param array<mixed> $data
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function formatResponse(
        string $format,
        array $data,
        GlueResponseTransfer $glueResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        foreach ($this->responseEncoders[$format] as $responseEncoder) {
            if (!$responseEncoder->accepts($data, $glueRequestTransfer)) {
                continue;
            }

            $glueResponseTransfer = $responseEncoder->encode($data, $glueResponseTransfer);

            return $glueResponseTransfer;
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return array<mixed>
     */
    protected function expandData(GlueResponseTransfer $glueResponseTransfer): array
    {
        $data = [];
        if ($glueResponseTransfer->getErrors()->count()) {
            foreach ($glueResponseTransfer->getErrors() as $glueErrorTransfer) {
                $data[] = $glueErrorTransfer->toArray(true, true);
            }

            return $data;
        }

        if ($glueResponseTransfer->getResources()->count() !== 0) {
            foreach ($glueResponseTransfer->getResources() as $resource) {
                $data[] = $resource->getAttributesOrFail()->toArray(true, true);
            }
        }

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return int
     */
    protected function getStatusCode(GlueRequestTransfer $glueRequestTransfer): int
    {
        switch ($glueRequestTransfer->getMethod()) {
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
