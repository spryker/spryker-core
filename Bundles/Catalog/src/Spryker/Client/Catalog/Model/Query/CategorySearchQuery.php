<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Query;

use Elastica\Filter\Term;
use Elastica\Index;
use Elastica\Query;
use Elastica\Query\Filtered;
use Spryker\Client\Search\Model\Query\QueryInterface;

class CategorySearchQuery implements QueryInterface
{

    /**
     * @var int
     */
    protected $idCategory;

    /**
     * @param int $idCategory
     */
    public function __construct($idCategory)
    {
        $this->idCategory = $idCategory;
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        $query = new Query();
        $query = $this->addCategoryFilterToQuery($query);
        $query->setSource(['search-result-data']);

        return $query;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Query
     */
    protected function addCategoryFilterToQuery(Query $query)
    {
        $query->setQuery(
            (new Filtered())
                ->setFilter(new Term([
                    'category.all-parents' => (int)$this->idCategory,
                ]))
        );

        return $query;
    }

}
