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

        if (!\defined('\Spryker\Shared\PriceProduct\PriceProductConstants::PRICE_DIMENSION_DEFAULT')) {
            return $this->formatSearchResultWithoutPriceDimensions($results);
        }

        $priceProductClient = $this->getFactory()->getPriceProductClient();
        $priceProductStorageClient = $this->getFactory()->getPriceProductStorageClient();
        foreach ($results['product_abstract'] as &$product) {
            $priceProductTransfersFromStorage = $priceProductStorageClient->getPriceProductAbstractTransfers($product['id_product_abstract']);
            $currentProductPriceTransfer = $priceProductClient->resolveProductPriceTransfer($priceProductTransfersFromStorage);
            $product['price'] = $currentProductPriceTransfer->getPrice();
            $product['prices'] = $currentProductPriceTransfer->getPrices();
        }

        return $results;
    }

    /**
     * Fallback method to work with PriceProduct module without price dimensions support.
     *
     * @param array $result
     *
     * @return mixed|array
     */
    protected function formatSearchResultWithoutPriceDimensions(array $result)
    {
        $priceProductClient = $this->getFactory()->getPriceProductClient();
        foreach ($result as &$product) {
            $currentProductPriceTransfer = $priceProductClient->resolveProductPrice($product['prices']);
            $product['price'] = $currentProductPriceTransfer->getPrice();
            $product['prices'] = $currentProductPriceTransfer->getPrices();
        }

        return $result;
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
