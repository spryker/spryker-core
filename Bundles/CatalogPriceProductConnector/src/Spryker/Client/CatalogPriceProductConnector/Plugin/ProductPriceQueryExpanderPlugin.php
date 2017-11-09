<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector\Plugin;

use Elastica\Query;
use Spryker\Client\Currency\CurrencyClient;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Price\PriceClient;
use Spryker\Client\PriceProduct\PriceProductClient;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Elastica\Query\BoolQuery;
use InvalidArgumentException;
use Elastica\Query\Type;
use Generated\Shared\Search\PageIndexMap;
use Elastica\Query\Term;
use Elastica\Query\Nested;

/**
 * @method \Spryker\Client\CatalogPriceProductConnector\CatalogPriceProductConnectorFactory getFactory()
 */
class ProductPriceQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $boolQuery = $this->getBoolQuery($searchQuery->getSearchQuery());

        $priceIdentifier = $this->getFactory()->createPriceIdentifierBuilder()
            ->buildIdentifierForCurrentCurrency();

        $termQuery = $this->createPriceTermQuery($priceIdentifier);
        $productPriceFilter = $this->createNestedProductPriceFilter($termQuery);

        $boolQuery->addFilter($productPriceFilter);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query)
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf(
                'Product Price Query Expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }

    /**
     * @param string $priceFacetName
     *
     * @return \Elastica\Query\Term
     */
    protected function createPriceTermQuery($priceFacetName)
    {
        return (new Term())
            ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, $priceFacetName);
    }

    /**
     * @param \Elastica\Query\Term $termQuery
     *
     * @return \Elastica\Query\Nested
     */
    protected function createNestedProductPriceFilter(Term $termQuery)
    {
        return (new Nested())
            ->setQuery($termQuery)
            ->setPath(PageIndexMap::INTEGER_FACET);
    }
}
