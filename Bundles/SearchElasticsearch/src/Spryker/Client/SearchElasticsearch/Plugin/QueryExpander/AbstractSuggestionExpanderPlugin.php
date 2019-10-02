<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Elastica\Suggest;
use Elastica\Suggest\AbstractSuggest;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchElasticsearch\Exception\MissingSuggestionQueryException;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
abstract class AbstractSuggestionExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands query with suggestions.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $this->setCompletionTerm($searchQuery->getSearchQuery(), $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param array $requestParameters
     *
     * @return void
     */
    protected function setCompletionTerm(Query $query, array $requestParameters = []): void
    {
        $this->assertQueryHasSuggestion($query);

        $suggestion = $this->createSuggestion($query, $requestParameters);
        if ($suggestion) {
            $this->addSuggestion($query, $suggestion);
        }
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \Spryker\Client\SearchElasticsearch\Exception\MissingSuggestionQueryException
     *
     * @return void
     */
    protected function assertQueryHasSuggestion(Query $query): void
    {
        if (!$query->hasParam('suggest')) {
            throw new MissingSuggestionQueryException(
                'The base query to be extended needs to have "suggest" parameter set. Use `$query->setSuggest()`.'
            );
        }
    }

    /**
     * @param \Elastica\Query $query
     * @param \Elastica\Suggest\AbstractSuggest $suggest
     *
     * @return void
     */
    protected function addSuggestion(Query $query, AbstractSuggest $suggest): void
    {
        $suggestion = $this->getSuggestion($query);
        $suggestion->addSuggestion($suggest);
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Suggest
     */
    protected function getSuggestion(Query $query): Suggest
    {
        return $query->getParam('suggest');
    }

    /**
     * @param \Elastica\Query $query
     * @param array $requestParameters
     *
     * @return \Elastica\Suggest\AbstractSuggest|null
     */
    abstract protected function createSuggestion(Query $query, array $requestParameters = []): ?AbstractSuggest;
}
