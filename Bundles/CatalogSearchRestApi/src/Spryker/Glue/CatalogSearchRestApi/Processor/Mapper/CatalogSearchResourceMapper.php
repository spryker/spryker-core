<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi\Processor\Mapper;

use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer;
use Generated\Shared\Transfer\RestFacetSearchResultAttributesTransfer;
use Generated\Shared\Transfer\RestRangeSearchResultAttributesTransfer;
use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CatalogSearchResourceMapper implements CatalogSearchResourceMapperInterface
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
     * @param array $restSearchResponse
     * @param string $currency
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapSearchResponseAttributesTransferToRestResponse(array $restSearchResponse, string $currency): RestResourceInterface
    {
        $restSearchAttributesTransfer = (new RestCatalogSearchAttributesTransfer())->fromArray($restSearchResponse, true);
        $restSearchAttributesTransfer->setCurrency($currency);
        if (isset($restSearchResponse['facets'])) {
            $restSearchAttributesTransfer = $this->mapSearchResponseFacetTransfersToSearchAttributesTransfer($restSearchResponse['facets'], $restSearchAttributesTransfer);
        }

        return $this->restResourceBuilder->createRestResource(
            CatalogSearchRestApiConfig::RESOURCE_CATALOG_SEARCH,
            null,
            $restSearchAttributesTransfer
        );
    }

    /**
     * @param array $facets
     * @param \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer $restSearchAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer
     */
    protected function mapSearchResponseFacetTransfersToSearchAttributesTransfer(array $facets, RestCatalogSearchAttributesTransfer $restSearchAttributesTransfer): RestCatalogSearchAttributesTransfer
    {
        $restSearchAttributesTransfer->setFacets(null);
        foreach ($facets as $facet) {
            if ($facet instanceof FacetSearchResultTransfer) {
                $restFacet = $this->createRestFacetSearchResultAttributesTransfer($facet);
                $restSearchAttributesTransfer->addFacet($restFacet->toArray());
                continue;
            }
            if ($facet instanceof RangeSearchResultTransfer) {
                $restFacet = $this->createRestRangeSearchResultAttributesTransfer($facet);
                $restSearchAttributesTransfer->addFacet($restFacet->toArray());
            }
        }

        return $restSearchAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer $facet
     *
     * @return \Generated\Shared\Transfer\RestFacetSearchResultAttributesTransfer
     */
    protected function createRestFacetSearchResultAttributesTransfer(FacetSearchResultTransfer $facet): RestFacetSearchResultAttributesTransfer
    {
        return (new RestFacetSearchResultAttributesTransfer())->fromArray($facet->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\RangeSearchResultTransfer $facet
     *
     * @return \Generated\Shared\Transfer\RestRangeSearchResultAttributesTransfer
     */
    protected function createRestRangeSearchResultAttributesTransfer(RangeSearchResultTransfer $facet): RestRangeSearchResultAttributesTransfer
    {
        return (new RestRangeSearchResultAttributesTransfer())->fromArray($facet->toArray(), true);
    }
}
