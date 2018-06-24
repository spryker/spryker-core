<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector\Plugin;

use Elastica\ResultSet;
use Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

/**
 * @method \Spryker\Client\CatalogPriceProductConnector\CatalogPriceProductConnectorFactory getFactory()
 */
class CurrencyAwareSuggestionByTypeResultFormatter extends AbstractElasticsearchResultFormatterPlugin
{
    protected const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface
     */
    protected $rawCatalogSearchResultFormatterPlugin;

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface $rawCatalogSearchResultFormatterPlugin
     */
    public function __construct(ResultFormatterPluginInterface $rawCatalogSearchResultFormatterPlugin)
    {
        $this->rawCatalogSearchResultFormatterPlugin = $rawCatalogSearchResultFormatterPlugin;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $results = $this->rawCatalogSearchResultFormatterPlugin->formatResult($searchResult, $requestParameters);

        if (!isset($results['product_abstract'])) {
            return $results;
        }

        $priceProductClient = $this->getFactory()->getPriceProductClient();
        foreach ($results['product_abstract'] as &$product) {
            $currentProductPriceTransfer = $priceProductClient->resolveProductPrice(
                [static::PRICE_DIMENSION_DEFAULT => $product['prices']]
            );
            $product['price'] = $currentProductPriceTransfer->getPrice();
            $product['prices'] = $currentProductPriceTransfer->getPrices();
        }

        return $results;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return $this->rawCatalogSearchResultFormatterPlugin->getName();
    }
}
