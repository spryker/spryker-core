<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use Generated\Shared\Search\PageIndexMap;

// TODO: this can be removed?
class CategorySearchQuery extends AbstractCatalogSearchQuery
{

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @var string
     */
    protected $searchString;

    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @param int $idCategory
     * @param string|null $searchString
     */
    public function __construct($idCategory, $searchString = null)
    {
        $this->idCategory = $idCategory;
        $this->searchString = $searchString;
        $this->query = $this->createSearchQuery();
    }

    /**
     * @param array $requestParameters
     *
     * @return \Elastica\Query
     */
    public function getSearchQuery(array $requestParameters = [])
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
        $termQuery = (new Term())->setParam(PageIndexMap::CATEGORY_ALL_PARENTS, (int)$this->idCategory);

        $boolQuery = (new BoolQuery())
            ->addMust($termQuery);

        if ($this->searchString !== null) {
            $matchQuery = $this->createFulltextSearchQuery($this->searchString);
            $boolQuery->addMust($matchQuery);
        }

        $query->setQuery($boolQuery);

        return $query;
    }

}
