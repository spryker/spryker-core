<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector\Plugin\Catalog\QueryExpander;

use Generated\Shared\Transfer\SearchQueryValueFacetFilterTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\CatalogPriceProductConnector\CatalogPriceProductConnectorFactory getFactory()
 */
class ProductPriceSearchHttpQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string
     */
    public const FIELD_NAME_CURRENCY = 'currency';

    /**
     * @var string
     */
    public const FIELD_NAME_PRICE_MODE = 'price_mode';

    /**
     * {@inheritDoc}
     * - Extends query with price and currency data.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $searchQuery = $this->addCurrencyFilter($searchQuery);

        return $this->addPriceModeFilter($searchQuery);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addCurrencyFilter(QueryInterface $searchQuery): QueryInterface
    {
        $currency = $this->getFactory()->getCurrencyClient()->getCurrent()->getCode();

        $searchQuery->getSearchQuery()->addSearchQueryFacetFilter(
            (new SearchQueryValueFacetFilterTransfer())
                ->setFieldName(static::FIELD_NAME_CURRENCY)
                ->addValue($currency),
        );

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addPriceModeFilter(QueryInterface $searchQuery): QueryInterface
    {
        $priceMode = $this->getFactory()->getPriceClient()->getCurrentPriceMode();

        $searchQuery->getSearchQuery()->addSearchQueryFacetFilter(
            (new SearchQueryValueFacetFilterTransfer())
                ->setFieldName(static::FIELD_NAME_PRICE_MODE)
                ->addValue($priceMode),
        );

        return $searchQuery;
    }
}
