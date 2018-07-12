<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestSearchAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SearchRestApi\SearchRestApiConfig;

class SearchResourceMapper implements SearchResourceMapperInterface
{
    protected const QUERY_STRING_PARAMETER = 'q';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
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
    public function mapRestSearchAttributesTransferToSearchString(RestRequestInterface $restRequest): string
    {
        return $restRequest->getHttpRequest()->query->get(static::QUERY_STRING_PARAMETER, '');
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    public function mapRestSearchAttributesTransferToSearchRequestParameters(RestRequestInterface $restRequest): array
    {
        return $restRequest->getHttpRequest()->query->all();
    }

    /**
     * @param array $restSearchResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapSearchResponseAttributesTransferToRestResponse(array $restSearchResponse): RestResourceInterface
    {
        $restSearchAttributesTransfer = (new RestSearchAttributesTransfer())->fromArray($restSearchResponse, true);
        $restSearchAttributesTransfer->setFacets([]);
        foreach ($restSearchResponse['facets'] as $facetTransfer) {
            $restSearchAttributesTransfer->addFacets($facetTransfer->toArray(true, true));
        }

        return $this->restResourceBuilder->createRestResource(
            SearchRestApiConfig::RESOURCE_SEARCH,
            null,
            $restSearchAttributesTransfer
        );
    }
}
