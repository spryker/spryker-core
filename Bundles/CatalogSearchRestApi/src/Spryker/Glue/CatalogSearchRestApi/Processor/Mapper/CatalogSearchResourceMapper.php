<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi\Processor\Mapper;

use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\PriceModeConfigurationTransfer;
use Generated\Shared\Transfer\RangeSearchResultTransfer;
use Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer;
use Generated\Shared\Transfer\RestCatalogSearchSortTransfer;
use Generated\Shared\Transfer\RestCurrencyTransfer;
use Generated\Shared\Transfer\RestFacetSearchResultTransfer;
use Generated\Shared\Transfer\RestPriceProductTransfer;
use Generated\Shared\Transfer\RestRangeSearchResultTransfer;
use Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientInterface;

class CatalogSearchResourceMapper implements CatalogSearchResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @param \Spryker\Glue\CatalogSearchRestApi\Dependency\Client\CatalogSearchRestApiToCurrencyClientInterface $currencyClient
     */
    public function __construct(CatalogSearchRestApiToCurrencyClientInterface $currencyClient)
    {
        $this->currencyClient = $currencyClient;
    }

    /**
     * @uses \Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\FacetResultFormatterPlugin::NAME
     */
    protected const NAME = 'facets';

    /**
     * @uses \Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\SortedResultFormatterPlugin::NAME
     */
    protected const SORT_NAME = 'sort';

    /**
     * @param array $restSearchResponse
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer
     */
    public function mapSearchResultToRestAttributesTransfer(array $restSearchResponse): RestCatalogSearchAttributesTransfer
    {
        $restSearchAttributesTransfer = (new RestCatalogSearchAttributesTransfer())->fromArray($restSearchResponse, true);
        $restCatalogSearchSortTransfer = (new RestCatalogSearchSortTransfer())
            ->fromArray($restSearchResponse[static::SORT_NAME]->toArray());
        $restSearchAttributesTransfer->setSort($restCatalogSearchSortTransfer);

        if (isset($restSearchResponse[static::NAME])) {
            $restSearchAttributesTransfer = $this->mapSearchResponseFacetTransfersToSearchAttributesTransfer(
                $restSearchResponse[static::NAME],
                $restSearchAttributesTransfer
            );
        }

        return $restSearchAttributesTransfer;
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
                    (new RestFacetSearchResultTransfer())->fromArray($facet->toArray(), true)
                );
                continue;
            }
            if ($facet instanceof RangeSearchResultTransfer) {
                $restSearchAttributesTransfer->addRangeFacet(
                    (new RestRangeSearchResultTransfer())->fromArray($facet->toArray(), true)
                );
            }
        }

        return $restSearchAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer $restSearchAttributesTransfer
     * @param \Generated\Shared\Transfer\PriceModeConfigurationTransfer $priceModeInformation
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer
     */
    public function mapPrices(
        RestCatalogSearchAttributesTransfer $restSearchAttributesTransfer,
        PriceModeConfigurationTransfer $priceModeInformation
    ): RestCatalogSearchAttributesTransfer {
        foreach ($restSearchAttributesTransfer->getProducts() as $product) {
            $prices = [];
            foreach ($product->getPrices() as $priceType => $price) {
                $priceData = $this
                    ->getPriceTransfer($priceType, $price, $priceModeInformation)
                    ->modifiedToArray(true, true);

                $prices[] = $priceData + [$priceType => $price];
            }
            $product->setPrices($prices);
        }

        return $restSearchAttributesTransfer;
    }

    /**
     * @param string $priceType
     * @param int $price
     * @param \Generated\Shared\Transfer\PriceModeConfigurationTransfer $priceModeInformation
     *
     * @return \Generated\Shared\Transfer\RestPriceProductTransfer
     */
    protected function getPriceTransfer(
        string $priceType,
        int $price,
        PriceModeConfigurationTransfer $priceModeInformation
    ): RestPriceProductTransfer {
        $restPriceProductTransfer = new RestPriceProductTransfer();
        $restPriceProductTransfer->setPriceTypeName($priceType);

        $restPriceProductTransfer->setCurrency(
            (new RestCurrencyTransfer())->fromArray(
                $this->currencyClient->getCurrent()->toArray(),
                true
            )
        );

        if ($priceModeInformation->getCurrentPriceMode() === $priceModeInformation->getGrossModeIdentifier()) {
            return $restPriceProductTransfer->setGrossAmount($price);
        }

        if ($priceModeInformation->getCurrentPriceMode() === $priceModeInformation->getNetModeIdentifier()) {
            return $restPriceProductTransfer->setNetAmount($price);
        }

        return $restPriceProductTransfer;
    }
}
