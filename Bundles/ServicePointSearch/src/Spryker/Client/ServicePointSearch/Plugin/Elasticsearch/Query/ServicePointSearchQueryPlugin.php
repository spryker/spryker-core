<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\Term;
use Elastica\Query\Wildcard;
use Elastica\Suggest;
use Generated\Shared\Search\ServicePointIndexMap;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringGetterInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Shared\ServicePointSearch\ServicePointSearchConfig;

/**
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchConfig getConfig()
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchFactory getFactory()
 */
class ServicePointSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface, SearchStringSetterInterface, SearchStringGetterInterface
{
    /**
     * @uses \Spryker\Shared\ServicePointSearch\ServicePointSearchConfig::SERVICE_POINT_RESOURCE_NAME
     *
     * @var string
     */
    protected const SOURCE_IDENTIFIER = 'service_point';

    /**
     * @var \Elastica\Query
     */
    protected Query $query;

    /**
     * @var string|null
     */
    protected ?string $searchString = null;

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer|null
     */
    protected ?SearchContextTransfer $searchContextTransfer = null;

    public function __construct()
    {
        $this->query = $this->createQuery();
    }

    /**
     * {@inheritDoc}
     * - Returns query object for Service Point search.
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
     * - Defines context for Service Point search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        if (!$this->searchContextTransfer) {
            $this->searchContextTransfer = (new SearchContextTransfer())->setSourceIdentifier(static::SOURCE_IDENTIFIER);
        }

        return $this->searchContextTransfer;
    }

    /**
     * {@inheritDoc}
     * - Sets context for Service Point search.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchString
     *
     * @return void
     */
    public function setSearchString($searchString): void
    {
        $this->searchString = $searchString;
        $this->query = $this->createQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSearchString(): ?string
    {
        return $this->searchString;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createQuery(): Query
    {
        $query = new BoolQuery();
        $query = $this->addTypeQuery($query);
        $query = $this->addFullTextQuery($query);
        $suggest = (new Suggest())->setGlobalText((string)$this->getSearchString());

        return (new Query())
            ->setQuery($query)
            ->setSuggest($suggest)
            ->setSource(ServicePointIndexMap::SEARCH_RESULT_DATA);
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function addTypeQuery(BoolQuery $boolQuery): BoolQuery
    {
        $typeQuery = (new Term())->setTerm(
            ServicePointIndexMap::TYPE,
            ServicePointSearchConfig::SERVICE_POINT_RESOURCE_NAME,
        );

        return $boolQuery->addMust($typeQuery);
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function addFullTextQuery(BoolQuery $boolQuery): BoolQuery
    {
        if (!$this->searchString) {
            return $boolQuery;
        }

        $fullTextQuery = (new BoolQuery())
            ->addShould($this->createFullTextWildcard())
            ->addShould($this->createFullTextBoostedWildcard())
            ->addShould($this->createFulltextSearchQuery());

        return $boolQuery->addMust($fullTextQuery);
    }

    /**
     * @return \Elastica\Query\Wildcard
     */
    protected function createFullTextWildcard(): Wildcard
    {
        return new Wildcard(
            ServicePointIndexMap::FULL_TEXT,
            $this->createWildcardValue(),
        );
    }

    /**
     * @return \Elastica\Query\Wildcard
     */
    protected function createFullTextBoostedWildcard(): Wildcard
    {
        return new Wildcard(
            ServicePointIndexMap::FULL_TEXT_BOOSTED,
            $this->createWildcardValue(),
            $this->getFactory()->getServicePointSearchConfig()->getElasticsearchFullTextBoostedBoostingValue(),
        );
    }

    /**
     * @return \Elastica\Query\MultiMatch
     */
    protected function createFulltextSearchQuery(): MultiMatch
    {
        $fields = [
            ServicePointIndexMap::FULL_TEXT,
            sprintf(
                '%s^%d',
                ServicePointIndexMap::FULL_TEXT_BOOSTED,
                $this->getFactory()->getServicePointSearchConfig()->getElasticsearchFullTextBoostedBoostingValue(),
            ),
        ];

        return (new MultiMatch())
            ->setFields($fields)
            ->setQuery($this->searchString)
            ->setType(MultiMatch::TYPE_PHRASE_PREFIX);
    }

    /**
     * @return string
     */
    protected function createWildcardValue(): string
    {
        return sprintf('*%s*', (string)$this->searchString);
    }
}
