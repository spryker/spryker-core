<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Suggest;

class SuggestionQueryPlugin extends CatalogSearchQueryPlugin
{

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery()
    {
        $query = parent::createSearchQuery();
        $query = $this->setSuggestFulltextSearch($query);

        return $query;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Query
     */
    protected function setSuggestFulltextSearch(Query $query)
    {
        if (!empty($this->searchString)) {
            $query->setSuggest($this->createSuggest());
        }

        return $query;
    }

    /**
     * @return \Elastica\Suggest
     */
    protected function createSuggest()
    {
        $suggest = new Suggest();
        $suggest->setGlobalText($this->searchString);

        return $suggest;
    }

}
