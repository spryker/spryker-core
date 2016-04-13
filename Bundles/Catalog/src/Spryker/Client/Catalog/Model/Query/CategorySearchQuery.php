<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Query;

use Elastica\Index;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use Generated\Shared\Search\Catalog\PageIndexMap;
use Spryker\Client\Search\Model\Query\QueryInterface;

class CategorySearchQuery implements QueryInterface
{

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @param int $idCategory
     */
    public function __construct($idCategory)
    {
        $this->idCategory = $idCategory;
        $this->query = $this->createSearchQuery();
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        return $this->query;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery()
    {
        $query = new Query();

        $query = $this->addCategoryFilterToQuery($query);
        $query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Query
     */
    protected function addCategoryFilterToQuery(Query $query)
    {
        $term = (new Term())->setParam(PageIndexMap::CATEGORY_ALL_PARENTS, (int)$this->idCategory);
        $boolQuery = (new BoolQuery())->addMust($term);

        $query->setQuery($boolQuery);

        return $query;
    }

}
