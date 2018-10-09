<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\HttpFoundation\Request;

class RequestResourceExtractor implements RequestResourceExtractorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface
     */
    protected $decoderMatcher;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface $decoderMatcher
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        DecoderMatcherInterface $decoderMatcher
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->decoderMatcher = $decoderMatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function extract(Request $request, MetadataInterface $metadata): RestResourceInterface
    {
        $resource = $this->processPostData($request, $metadata);

        if (!$resource) {
            $resource = $this->restResourceBuilder->createRestResource(
                $request->attributes->get(RequestConstantsInterface::ATTRIBUTE_TYPE, 'errors'),
                $request->attributes->get(RequestConstantsInterface::ATTRIBUTE_ID)
            );
        }

        return $resource;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    protected function processPostData(
        Request $request,
        MetadataInterface $metadata
    ): ?RestResourceInterface {
        $requestData = $this->readRequestData($request, $metadata);
        if (!$requestData) {
            return null;
        }

        $data = $requestData[RestResourceInterface::RESOURCE_DATA];

        if (!isset($data[RestResourceInterface::RESOURCE_TYPE]) ||
            !isset($data[RestResourceInterface::RESOURCE_ATTRIBUTES])) {
            return null;
        }

        return $this->restResourceBuilder->createRestResource(
            $data[RestResourceInterface::RESOURCE_TYPE],
            $request->attributes->get(RequestConstantsInterface::ATTRIBUTE_ID),
            $this->mapEntityTransfer($request, $data)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $data
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    protected function mapEntityTransfer(Request $request, array $data): ?AbstractTransfer
    {
        $className = $request->attributes->get(RequestConstantsInterface::ATTRIBUTE_RESOURCE_FQCN);

        if (!$className) {
            return null;
        }

        $restResourceAttributesTransfer = new $className();
        if ($restResourceAttributesTransfer instanceof AbstractTransfer) {
            $restResourceAttributesTransfer->fromArray($data[RestResourceInterface::RESOURCE_ATTRIBUTES], true);
        }

        return $restResourceAttributesTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return array|null
     */
    protected function readRequestData(Request $request, MetadataInterface $metadata): ?array
    {
        $rawPostData = (string)$request->getContent();

        if (!$rawPostData) {
            return null;
        }

        $decoder = $this->decoderMatcher->match($metadata);
        if (!$decoder) {
            return null;
        }

        $requestData = $decoder->decode($rawPostData);
        if (!isset($requestData[RestResourceInterface::RESOURCE_DATA])) {
            return null;
        }

        return $requestData;
    }

    /**
     * @param string $mainResourceType
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function extractParentResources(string $mainResourceType, Request $request): array
    {
        $allResources = (array)$request->attributes->get(RequestConstantsInterface::ATTRIBUTE_ALL_RESOURCES);
        if (count($allResources) === 1) {
            return [];
        }
        $resources = [];
        foreach ($allResources as $resource) {
            if (!$resource[RequestConstantsInterface::ATTRIBUTE_ID]) {
                continue;
            }

            if ($mainResourceType === $resource[RequestConstantsInterface::ATTRIBUTE_TYPE]) {
                continue;
            }

            $resources[] = $this->restResourceBuilder->createRestResource(
                $resource[RequestConstantsInterface::ATTRIBUTE_TYPE],
                $resource[RequestConstantsInterface::ATTRIBUTE_ID]
            );
        }

        return $resources;
    }
}
