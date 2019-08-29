<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 * @method \Spryker\Client\Catalog\CatalogConfig getConfig()
 */
class ProductConcreteCatalogSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface, SearchStringSetterInterface
{
    protected const SOURCE_NAME = 'page';

    /**
     * @uses \Spryker\Shared\ProductPageSearch\ProductPageSearchConstants::PRODUCT_CONCRETE_RESOURCE_NAME
     */
    protected const PRODUCT_CONCRETE_RESOURCE_NAME = 'product_concrete';

    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var string
     */
    protected $searchString = '';

    /**
     * Specification:
     * - Builds score based on multimatch cross fileds query type.
     */
    public function __construct()
    {
        $this->createQuery();
    }

    /**
     * {@inheritdoc}
     * - Returns a query object for concrete products catalog search.
     *
     * @api
     *
     * @return \Elastica\Query
     */
    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     * - Builds score based on multimatch cross fileds query type.
     *
     * @api
     *
     * @param string $searchString
     *
     * @return void
     */
    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
        $this->createQuery();
    }

    /**
     * {@inheritdoc}
     * - Defines a context for concrete products catalog search.
     *
     * @api
     *
     * @deprecated This method will be moved to `\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface`.
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        $elasticsearchSearchContextTransfer = new ElasticsearchSearchContextTransfer();
        $elasticsearchSearchContextTransfer->setSourceName(static::SOURCE_NAME);
        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setElasticsearchContext($elasticsearchSearchContextTransfer);

        return $searchContextTransfer;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createQuery(): Query
    {
        $this->query = new Query();
        $this->addFulltextSearchToQuery();
        $this->setQuerySource();

        return $this->query;
    }

    /**
     * @return void
     */
    protected function addFulltextSearchToQuery(): void
    {
        $matchQuery = $this->createFulltextSearchQuery();
        $boolQuery = $this->createBoolQuery($matchQuery);
        $this->query->setQuery($boolQuery);
    }

    /**
     * @return \Elastica\Query\AbstractQuery
     */
    protected function createFulltextSearchQuery(): AbstractQuery
    {
        if ($this->searchString === '') {
            return new MatchAll();
        }

        $fields = [
            PageIndexMap::FULL_TEXT_BOOSTED . '^' . $this->getFullTextBoostedBoostingValue(),
        ];

        $matchQuery = (new MultiMatch())
            ->setFields($fields)
            ->setQuery($this->searchString)
            ->setType(MultiMatch::TYPE_CROSS_FIELDS);

        return $matchQuery;
    }

    /**
     * @param \Elastica\Query\AbstractQuery $matchQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function createBoolQuery(AbstractQuery $matchQuery): AbstractQuery
    {
        $boolQuery = new BoolQuery();
        $boolQuery->addMust($matchQuery);
        $boolQuery = $this->setTypeFilter($boolQuery);

        return $boolQuery;
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function setTypeFilter(BoolQuery $boolQuery): BoolQuery
    {
        $typeFilter = new Match();
        $typeFilter->setField(PageIndexMap::TYPE, static::PRODUCT_CONCRETE_RESOURCE_NAME);
        $boolQuery->addMust($typeFilter);

        return $boolQuery;
    }

    /**
     * @return void
     */
    protected function setQuerySource(): void
    {
        $this->query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);
    }

    /**
     * @return int
     */
    protected function getFullTextBoostedBoostingValue(): int
    {
        return $this->getFactory()
            ->getCatalogConfig()
            ->getFullTextBoostedBoostingValue();
    }
}
