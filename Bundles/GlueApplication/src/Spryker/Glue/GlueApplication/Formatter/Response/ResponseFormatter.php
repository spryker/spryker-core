<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Formatter\Response;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;

class ResponseFormatter implements ResponseFormatterInterface
{
    /**
     * @var array<string, \Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface>
     */
    protected array $responseEncoderStrategies = [];

    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected GlueApplicationConfig $glueApplicationConfig;

    /**
     * @param array<\Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface> $responseEncoderStrategies
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $glueApplicationConfig
     */
    public function __construct(
        array $responseEncoderStrategies,
        GlueApplicationConfig $glueApplicationConfig
    ) {
        array_map(function (ResponseEncoderStrategyInterface $responseEncoderStrategy): void {
            $this->responseEncoderStrategies[$responseEncoderStrategy->getAcceptedType()] = $responseEncoderStrategy;
        }, $responseEncoderStrategies);

        $this->glueApplicationConfig = $glueApplicationConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface|null $resource
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function format(
        GlueResponseTransfer $glueResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer,
        ?ResourceInterface $resource = null
    ): GlueResponseTransfer {
        if (!$glueResponseTransfer->getHttpStatus()) {
            $glueResponseTransfer->setHttpStatus($this->getStatusCode($glueRequestTransfer));
        }

        if ($glueResponseTransfer->getContent()) {
            return $glueResponseTransfer;
        }

        if (!array_key_exists($glueRequestTransfer->getAcceptedFormat(), $this->responseEncoderStrategies)) {
            $glueRequestTransfer->setAcceptedFormat(
                $this->glueApplicationConfig->getDefaultResponseFormat(),
            );
        }
        $isSnakeCased = $this->getIsSnakeCased($glueRequestTransfer, $resource);
        $data = $this->expandData($glueResponseTransfer, $isSnakeCased);

        return $this->formatResponse($glueResponseTransfer, $glueRequestTransfer->getAcceptedFormat(), $data);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param string $format
     * @param array<mixed> $data
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function formatResponse(
        GlueResponseTransfer $glueResponseTransfer,
        string $format,
        array $data
    ): GlueResponseTransfer {
        if (array_key_exists($format, $this->responseEncoderStrategies)) {
            return $this->responseEncoderStrategies[$format]->encode($data, $glueResponseTransfer);
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param bool $isSnakeCased
     *
     * @return array<mixed>
     */
    protected function expandData(GlueResponseTransfer $glueResponseTransfer, bool $isSnakeCased): array
    {
        $data = [];
        if ($glueResponseTransfer->getErrors()->count()) {
            foreach ($glueResponseTransfer->getErrors() as $glueErrorTransfer) {
                $data[] = $glueErrorTransfer->toArray(true, !$isSnakeCased);
            }

            return $data;
        }

        if ($glueResponseTransfer->getResources()->count() !== 0) {
            foreach ($glueResponseTransfer->getResources() as $resource) {
                $data[] = $resource->getAttributesOrFail()->toArray(true, !$isSnakeCased);
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

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface|null $resource
     *
     * @return bool
     */
    protected function getIsSnakeCased(
        GlueRequestTransfer $glueRequestTransfer,
        ?ResourceInterface $resource = null
    ): bool {
        if ($resource === null) {
            return false;
        }
        if ($glueRequestTransfer->getResource() === null) {
            return false;
        }
        $method = $glueRequestTransfer->getResource()->getMethod();
        $declaredMethods = $resource->getDeclaredMethods();

        if (!$declaredMethods->offsetExists($method)) {
            return false;
        }

        return (bool)$declaredMethods->offsetGet($method)->getIsSnakeCased();
    }
}
