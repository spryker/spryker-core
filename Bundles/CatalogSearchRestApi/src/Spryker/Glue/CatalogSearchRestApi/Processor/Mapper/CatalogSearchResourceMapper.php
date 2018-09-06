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
use Generated\Shared\Transfer\RestPriceProductAttributesTransfer;
use Generated\Shared\Transfer\RestRangeSearchResultAttributesTransfer;
use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToPriceClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CatalogSearchResourceMapper implements CatalogSearchResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToPriceClientInterface $priceClient
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder, CatalogSearchRestApiToPriceClientInterface $priceClient)
    {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->priceClient = $priceClient;
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
        $restSearchAttributesTransfer = $this->mapPrices($restSearchAttributesTransfer);
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
        foreach ($facets as $facet) {
            if ($facet instanceof FacetSearchResultTransfer) {
                $restSearchAttributesTransfer->addValueFacet(
                    (new RestFacetSearchResultAttributesTransfer())->fromArray($facet->toArray(), true)
                );
                continue;
            }
            if ($facet instanceof RangeSearchResultTransfer) {
                $restSearchAttributesTransfer->addRangeFacet(
                    (new RestRangeSearchResultAttributesTransfer())->fromArray($facet->toArray(), true)
                );
            }
        }

        return $restSearchAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer $restSearchAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer
     */
    protected function mapPrices(RestCatalogSearchAttributesTransfer $restSearchAttributesTransfer): RestCatalogSearchAttributesTransfer
    {
        foreach ($restSearchAttributesTransfer->getProducts() as $product) {
            $prices = [];
            foreach ($product->getPrices() as $priceType => $price) {
                $prices[] = $this->getPriceTransfer($priceType, $price);
            }
            $product->setPrices($prices);
        }

        return $restSearchAttributesTransfer;
    }

    /**
     * @param string $priceType
     * @param int $price
     *
     * @return array
     */
    protected function getPriceTransfer(string $priceType, int $price): array
    {
        $currentPriceMode = $this->priceClient->getCurrentPriceMode();

        $restPriceProductAttributes = new RestPriceProductAttributesTransfer();
        $restPriceProductAttributes->setPriceTypeName($priceType);
        if ($currentPriceMode == $this->priceClient->getGrossPriceModeIdentifier()) {
            $restPriceProductAttributes->setGrossAmount($price);
        } elseif ($currentPriceMode == $this->priceClient->getGrossPriceModeIdentifier()) {
            $restPriceProductAttributes->setNetAmount($price);
        }

        return $restPriceProductAttributes->modifiedToArray(true, true)
            + [$priceType => $price];
    }
}
