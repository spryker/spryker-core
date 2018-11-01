<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Suggest;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;

class ProductCatalogSearchQueryPlugin extends CatalogSearchQueryPlugin
{
    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return \Elastica\Query
     */
    protected function addFulltextSearchToQuery(Query $baseQuery)
    {
        $baseQuery = parent::addFulltextSearchToQuery($baseQuery);

        $this->setTypeFilter($baseQuery->getQuery());
        $this->setSuggestion($baseQuery);

        return $baseQuery;
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return void
     */
    protected function setTypeFilter(BoolQuery $boolQuery)
    {
        $typeFilter = (new Match())
            ->setField(PageIndexMap::TYPE, ProductPageSearchConstants::PRODUCT_ABSTRACT_RESOURCE_NAME);

        $boolQuery->addMust($typeFilter);
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return void
     */
    protected function setSuggestion(Query $baseQuery)
    {
        $suggest = new Suggest();
        $suggest->setGlobalText((string)$this->getSearchString());

        $baseQuery->setSuggest($suggest);
    }
}
