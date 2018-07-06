<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Search;

use Generated\Shared\Transfer\RestSearchRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToSearchClientInterface;
use Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface;

class SearchReader implements SearchReaderInterface
{
    /**
     * @var \Spryker\Glue\SearchRestApi\Dependency\Client\
     */
    protected $searchClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface
     */
    protected $searchResourceMapper;

    /**
     * @param \Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToSearchClientInterface $searchClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SearchRestApi\Processor\Mapper\SearchResourceMapperInterface $searchResourceMapper
     */
    public function __construct(
        SearchRestApiToSearchClientInterface $searchClient,
        RestResourceBuilderInterface $restResourceBuilder,
        SearchResourceMapperInterface $searchResourceMapper
    ) {
        $this->searchClient = $searchClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->searchResourceMapper = $searchResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function search(RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer): RestResponseInterface
    {
    }
}
