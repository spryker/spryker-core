<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestSearchRequestAttributesTransfer;
use Generated\Shared\Transfer\RestSearchResponseAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\SearchRestApi\SearchRestApiConfig;

class SearchResourceMapper implements SearchResourceMapperInterface
{
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
     * @param \Generated\Shared\Transfer\RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer
     *
     * @return string
     */
    public function mapRestSearchRequestAttributesTransferToSearchString(RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer): string
    {
        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer
     *
     * @return array
     */
    public function mapRestSearchRequestAttributesTransferToSearchRequestParameters(RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer): array
    {
        return [];
    }

    /**
     * @param array $restSearchResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapSearchResponseAttributesTransferToRestResponse(array $restSearchResponse): RestResourceInterface
    {
        $restSearchAttributesTransfer = (new RestSearchResponseAttributesTransfer())->fromArray($restSearchResponse, true);
        return $this->restResourceBuilder->createRestResource(
            SearchRestApiConfig::RESOURCE_SEARCH,
            '0',
            $restSearchAttributesTransfer
        );
    }
}
