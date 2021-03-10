<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundlePageSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use Spryker\Shared\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig;

class ConfigurableBundleTemplatePageSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
    protected const SOURCE_IDENTIFIER = 'page';

    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer
     */
    protected $configurableBundleTemplatePageSearchRequestTransfer;

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected $searchContextTransfer;

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer
     */
    public function __construct(ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer)
    {
        $this->configurableBundleTemplatePageSearchRequestTransfer = $configurableBundleTemplatePageSearchRequestTransfer;
        $this->query = $this->createSearchQuery();
    }

    /**
     * {@inheritDoc}
     * - Returns query object for Configurable Bundle Template page search.
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
     * {@inheritDoc}
     * - Defines context for Configurable Bundle Template page search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        if (!$this->hasSearchContext()) {
            $this->setupDefaultSearchContext();
        }

        return $this->searchContextTransfer;
    }

    /**
     * {@inheritDoc}
     * - Sets context for Configurable Bundle Template page search.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery(): Query
    {
        $query = new Query();

        $query = $this->setQuery($query);
        $query = $query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return \Elastica\Query
     */
    protected function setQuery(Query $baseQuery): Query
    {
        $boolQuery = new BoolQuery();
        $boolQuery = $this->setTypeFilter($boolQuery);

        return $baseQuery->setQuery($boolQuery);
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function setTypeFilter(BoolQuery $boolQuery): BoolQuery
    {
        $typeFilter = $this->getMatchQuery()
            ->setField(PageIndexMap::TYPE, ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME);

        return $boolQuery->addMust($typeFilter);
    }

    /**
     * @return void
     */
    protected function setupDefaultSearchContext(): void
    {
        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setSourceIdentifier(static::SOURCE_IDENTIFIER);

        $this->searchContextTransfer = $searchContextTransfer;
    }

    /**
     * @return bool
     */
    protected function hasSearchContext(): bool
    {
        return (bool)$this->searchContextTransfer;
    }

    /**
     * For compatibility with PHP 8.
     *
     * @return \Elastica\Query\MatchQuery|\Elastica\Query\Match
     */
    protected function getMatchQuery()
    {
        $matchQueryClassName = class_exists(MatchQuery::class)
            ? MatchQuery::class
            : '\Elastica\Query\Match';

        return new $matchQueryClassName();
    }
}
