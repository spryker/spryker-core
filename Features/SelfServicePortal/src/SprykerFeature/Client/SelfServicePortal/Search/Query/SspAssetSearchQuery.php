<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Search\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\Term;
use Elastica\Query\Wildcard;
use Elastica\Suggest;
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
        $query = new BoolQuery();
        $query = $this->addTypeQuery($query);
        $query = $this->addFullTextQuery($query, $searchString);
        $suggest = (new Suggest())->setGlobalText((string)$searchString);

        return (new Query())
            ->setQuery($query)
            ->setSuggest($suggest)
            ->setSource(SspAssetIndexMap::SEARCH_RESULT_DATA);
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
            ->addShould($this->createFullTextWildcard($searchString))
            ->addShould($this->createFullTextBoostedWildcard($searchString))
            ->addShould($this->createFulltextSearchQuery($searchString));

        return $boolQuery->addMust($fullTextQuery);
    }

    protected function createFullTextWildcard(string $searchString): Wildcard
    {
        return new Wildcard(
            SspAssetIndexMap::FULL_TEXT_BOOSTED,
            $this->createWildcardValue($searchString),
        );
    }

    protected function createFullTextBoostedWildcard(string $searchString): Wildcard
    {
        return new Wildcard(
            SspAssetIndexMap::FULL_TEXT_BOOSTED,
            $this->createWildcardValue($searchString),
            $this->config->getElasticsearchFullTextBoostedBoostingValue(),
        );
    }

    protected function createFulltextSearchQuery(string $searchString): MultiMatch
    {
        $fields = [
            SspAssetIndexMap::FULL_TEXT_BOOSTED,
            sprintf(
                '%s^%d',
                SspAssetIndexMap::FULL_TEXT_BOOSTED,
                $this->config->getElasticsearchFullTextBoostedBoostingValue(),
            ),
        ];

        return (new MultiMatch())
            ->setFields($fields)
            ->setQuery($searchString)
            ->setType(MultiMatch::TYPE_PHRASE_PREFIX);
    }

    protected function createWildcardValue(string $searchString): string
    {
        return sprintf('*%s*', $searchString);
    }
}
