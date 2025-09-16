<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Search\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Elastica\Query\Prefix;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Elastica\Suggest;
use Elastica\Suggest\Term as SuggestTerm;
use Generated\Shared\Search\SspAssetIndexMap;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;

class SspAssetSearchQuery implements SspAssetSearchQueryInterface
{
    public function __construct(protected SelfServicePortalConfig $config)
    {
    }

    public function createQuery(?string $searchString): Query
    {
        $boolQuery = new BoolQuery();
        $boolQuery = $this->addTypeQuery($boolQuery);
        $boolQuery = $this->addFullTextQuery($boolQuery, $searchString);
        $suggest = $this->createSuggest((string)$searchString);

        $query = new Query();
        $query->setQuery($boolQuery);
        if ($suggest) {
            $query->setSuggest($suggest);
        }
        $query->setSource(SspAssetIndexMap::SEARCH_RESULT_DATA);

        return $query;
    }

    protected function addTypeQuery(BoolQuery $boolQuery): BoolQuery
    {
        $typeQuery = (new Term())->setTerm(
            SspAssetIndexMap::TYPE,
            SharedSelfServicePortalConfig::SSP_ASSET_RESOURCE_NAME,
        );

        return $boolQuery->addMust($typeQuery);
    }

    protected function addFullTextQuery(BoolQuery $boolQuery, ?string $searchString): BoolQuery
    {
        if (!$searchString) {
            return $boolQuery;
        }

        $fullTextQuery = (new BoolQuery())
            ->addShould($this->createTermMatchQuery($searchString))
            ->addShould($this->createCompletionMatchQuery($searchString))
            ->addShould($this->createCompletionPrefixQuery($searchString))
            ->addShould($this->createSuggestionMatchQuery($searchString));

        return $boolQuery->addMust($fullTextQuery);
    }

    protected function createSuggest(string $searchString): ?Suggest
    {
        if (!$searchString) {
            return null;
        }

        $suggest = new Suggest();
        $termSuggest = new SuggestTerm('suggestion', SspAssetIndexMap::SUGGESTION_TERMS);
        $termSuggest->setText($searchString);
        $suggest->addSuggestion($termSuggest);

        return $suggest;
    }

    protected function createTermMatchQuery(string $searchString): Terms
    {
        return new Terms(SspAssetIndexMap::FULL_TEXT_BOOSTED, [$searchString]);
    }

    protected function createCompletionMatchQuery(string $searchString): Terms
    {
        return new Terms(SspAssetIndexMap::COMPLETION_TERMS, [$searchString]);
    }

    protected function createCompletionPrefixQuery(string $searchString): Prefix
    {
        return new Prefix([
            SspAssetIndexMap::COMPLETION_TERMS => $searchString,
        ]);
    }

    protected function createSuggestionMatchQuery(string $searchString): MatchQuery
    {
        $matchQuery = new MatchQuery();
        $matchQuery->setField(SspAssetIndexMap::SUGGESTION_TERMS, [
            'query' => $searchString,
            'fuzziness' => 'AUTO',
            'boost' => $this->config->getElasticsearchFullTextBoostedBoostingValue(),
        ]);

        return $matchQuery;
    }
}
