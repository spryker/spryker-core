<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Catalog;

use Generated\Shared\Transfer\RestSearchRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCatalogClientInterface;
use Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface;

class CatalogReader implements CatalogReaderInterface
{
    /**
     * @var \Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCatalogClientInterface
     */
    protected $catalogClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface
     */
    protected $searchResourceMapper;

    /**
     * @param \Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToCatalogClientInterface $catalogClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface $searchResourceMapper
     */
    public function __construct(
        SearchRestApiToCatalogClientInterface $catalogClient,
        RestResourceBuilderInterface $restResourceBuilder,
        SearchResourceMapperInterface $searchResourceMapper
    ) {
        $this->catalogClient = $catalogClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->searchResourceMapper = $searchResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function catalogSearch(RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $searchString = $this->searchResourceMapper->mapRestSearchRequestAttributesTransferToSearchString($restSearchRequestAttributesTransfer);
        $requestParameters = $this->searchResourceMapper->mapRestSearchRequestAttributesTransferToSearchRequestParameters($restSearchRequestAttributesTransfer);
        $restSearchResponseAttributesTransfer = $this->catalogClient->catalogSearch($searchString, $requestParameters);

        $restResource = $this->searchResourceMapper->mapSearchResponseAttributesTransferToRestResponse($restSearchResponseAttributesTransfer);

        return $response->addResource($restResource);
    }
}
