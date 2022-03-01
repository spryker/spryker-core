<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Response;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueJsonApiConvention\Encoder\EncoderInterface;
use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig;
use Symfony\Component\HttpFoundation\Request;

class JsonGlueResponseFormatter implements JsonGlueResponseFormatterInterface
{
    /**
     * @var string
     */
    protected const RESPONSE_INCLUDED = 'included';

    /**
     * @var string
     */
    protected const RESPONSE_RELATIONSHIPS = 'relationships';

    /**
     * @var string
     */
    protected const RESPONSE_LINKS = 'links';

    /**
     * @var string
     */
    protected const RESPONSE_DATA = 'data';

    /**
     * @var string
     */
    protected const RESPONSE_ERRORS = 'errors';

    /**
     * @var string
     */
    protected const LINK_SELF = 'self';

    /**
     * @var string
     */
    protected const RESOURCE_TYPE = 'type';

    /**
     * @var string
     */
    protected const RESOURCE_ID = 'id';

    /**
     * @var string
     */
    protected const RESOURCE_ATTRIBUTES = 'attributes';

    /**
     * @var string
     */
    protected const RESOURCES = 'resources';

    /**
     * @var \Spryker\Glue\GlueJsonApiConvention\Encoder\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var \Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig
     */
    protected $jsonApiConventionConfig;

    /**
     * @var \Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatterInterface
     */
    protected $responseSparseFieldFormatter;

    /**
     * @param \Spryker\Glue\GlueJsonApiConvention\Encoder\EncoderInterface $jsonEncoder
     * @param \Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig $jsonApiConventionConfig
     * @param \Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatterInterface $responseSparseFieldFormatter
     */
    public function __construct(
        EncoderInterface $jsonEncoder,
        GlueJsonApiConventionConfig $jsonApiConventionConfig,
        ResponseSparseFieldFormatterInterface $responseSparseFieldFormatter
    ) {
        $this->jsonEncoder = $jsonEncoder;
        $this->jsonApiConventionConfig = $jsonApiConventionConfig;
        $this->responseSparseFieldFormatter = $responseSparseFieldFormatter;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param array<string, mixed> $sparseFields
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    public function formatResponseData(
        GlueResponseTransfer $glueResponseTransfer,
        array $sparseFields,
        GlueRequestTransfer $glueRequestTransfer
    ): string {
        $glueResources = $glueResponseTransfer->getResources()->getArrayCopy();
        $data = $this->getResourceData($glueResources, $glueRequestTransfer);
        $responseData = [];
        if ($this->isSingleObjectRequest($glueRequestTransfer, $glueResources)) {
            $responseData[static::RESPONSE_DATA] = $data[0];
        } else {
            $responseData[static::RESPONSE_DATA] = $data;
            $link = $this->buildCollectionLink($glueRequestTransfer);
            if ($link) {
                $responseData[static::RESPONSE_LINKS] = $link;
            }
        }

        if ($glueResponseTransfer->getIncludedRelationships()->count() !== 0) {
            $includedData = $this->getResourceData($glueResponseTransfer->getIncludedRelationships()->getArrayCopy(), $glueRequestTransfer);
            if ($includedData) {
                $responseData[static::RESPONSE_INCLUDED] = $includedData;
            }
        }

        if ($sparseFields) {
            $responseData = $this->responseSparseFieldFormatter->format($sparseFields, $responseData, $glueRequestTransfer->getResourceOrFail()->getId());
        }

        return $this->jsonEncoder->encode($responseData);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    public function formatResponseWithEmptyResource(GlueRequestTransfer $glueRequestTransfer): string
    {
        $responseData = [];
        $responseData[static::RESPONSE_DATA] = [];
        $responseData[static::RESPONSE_LINKS] = $this->buildCollectionLink($glueRequestTransfer);

        return $this->jsonEncoder->encode($responseData);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\GlueErrorTransfer> $glueErrorTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    public function formatErrorResponse(
        ArrayObject $glueErrorTransfers,
        GlueRequestTransfer $glueRequestTransfer
    ): string {
        $response = [];
        foreach ($glueErrorTransfers as $glueErrorTransfer) {
            $response[static::RESPONSE_ERRORS][] = $glueErrorTransfer->toArray();
        }

        return $this->jsonEncoder->encode($response);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<mixed>
     */
    protected function buildCollectionLink(GlueRequestTransfer $glueRequestTransfer): array
    {
        $idResource = $glueRequestTransfer->getResourceOrFail()->getId();

        if ($glueRequestTransfer->getMethod() === Request::METHOD_GET && $idResource === null) {
            $linkParts = [];
            $linkParts[] = $glueRequestTransfer->getResourceOrFail()->getResourceName();
            $queryString = $this->buildQueryString($glueRequestTransfer);

            return $this->formatLinks([
                static::LINK_SELF => implode('/', $linkParts) . $queryString,
            ]);
        }

        return [];
    }

    /**
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResources
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getResourceData(array $glueResources, GlueRequestTransfer $glueRequestTransfer): array
    {
        $resourcesData = [];
        foreach ($glueResources as $resource) {
            $resource = array_filter($resource->toArray(true, true));
            if (!array_key_exists(static::RESPONSE_LINKS, $resource)) {
                $resource[static::RESPONSE_LINKS] = $this->getResourceSelfLink($resource, $glueRequestTransfer);
            }

            if (isset($resource[static::RESPONSE_RELATIONSHIPS])) {
                $resource[static::RESPONSE_RELATIONSHIPS] = $this->filterResourceRelationships($resource[static::RESPONSE_RELATIONSHIPS]);
            }

            $resourcesData[] = $resource;
        }

        return $resourcesData;
    }

    /**
     * @param array<mixed> $resourceRelationships
     *
     * @return array<mixed>
     */
    protected function filterResourceRelationships(array $resourceRelationships): array
    {
        $allowedResourceRelationshipKeys = [
            static::RESOURCE_TYPE,
            static::RESOURCE_ID,
        ];
        $filteredResourceRelationships = [];

        foreach ($resourceRelationships as $resourceRelationship) {
            foreach ($resourceRelationship[static::RESOURCES] as $resource) {
                $filteredResourceRelationships[$resource[static::RESOURCE_TYPE]][static::RESPONSE_DATA][] = array_filter(
                    $resource,
                    fn ($key) => in_array($key, $allowedResourceRelationshipKeys),
                    ARRAY_FILTER_USE_KEY,
                );
            }
        }

        return $filteredResourceRelationships;
    }

    /**
     * @param array<string, mixed> $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<string, string>
     */
    protected function getResourceSelfLink(array $resource, GlueRequestTransfer $glueRequestTransfer): array
    {
        $link = $resource[static::RESOURCE_TYPE];
        if ($resource[static::RESOURCE_ID]) {
            $link .= '/' . $resource[static::RESOURCE_ID];
        }
        $queryString = $this->buildQueryString($glueRequestTransfer);

        return $this->formatLinks([static::LINK_SELF => $link . $queryString]);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResources
     *
     * @return bool
     */
    protected function isSingleObjectRequest(GlueRequestTransfer $glueRequestTransfer, array $glueResources): bool
    {
        $resourceId = $glueRequestTransfer->getResourceOrFail()->getId();

        return count($glueResources) === 1 && ($resourceId || $glueRequestTransfer->getResourceOrFail()->getMethod() === strtolower(Request::METHOD_POST));
    }

    /**
     * @param array<string, string> $links
     *
     * @return array<string, string>
     */
    protected function formatLinks(array $links): array
    {
        $formattedLinks = [];

        $domainName = $this->jsonApiConventionConfig->getGlueDomain();

        foreach ($links as $key => $link) {
            $formattedLinks[$key] = $domainName . '/' . $link;
        }

        return $formattedLinks;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    protected function buildQueryString(GlueRequestTransfer $glueRequestTransfer): string
    {
        $queryFields = $glueRequestTransfer->getQueryFields();
        $queryString = '';

        if ($queryFields) {
            $queryString = urldecode(http_build_query($queryFields));

            if (mb_strlen($queryString)) {
                $queryString = '?' . $queryString;
            }
        }

        return $queryString;
    }
}
