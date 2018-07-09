<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestSuggestionsResponseAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SearchRestApi\SearchRestApiConfig;

class SuggestionsResourceMapper implements SuggestionsResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * SuggestionsResourceMapper constructor.
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string
     */
    public function mapRestSuggestionsRequestAttributesTransferToSuggestionsString(RestRequestInterface $restRequest): string
    {
        return $restRequest->getResource()->getId() ?? '';
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    public function mapRestSuggestionsRequestAttributesTransferToSuggestionsRequestParameters(RestRequestInterface $restRequest): array
    {
        $attributes = $restRequest->getResource()->getAttributes();
        if ($attributes) {
            return $attributes->toArray();
        }

        return [];
    }

    /**
     * @param array $restSearchResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapSuggestionsResponseAttributesTransferToRestResponse(array $restSearchResponse): RestResourceInterface
    {
        $restSuggestionsAttributesTransfer = (new RestSuggestionsResponseAttributesTransfer())->fromArray($restSearchResponse, true);
        return $this->restResourceBuilder->createRestResource(
            SearchRestApiConfig::RESOURCE_SUGGESTIONS,
            '0',
            $restSuggestionsAttributesTransfer
        );
    }
}
