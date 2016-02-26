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

class FulltextSearchQuery implements QueryInterface
{

    /**
     * @var string
     */
    protected $searchString;

    /**
     * @param string $searchString
     */
    public function __construct($searchString)
    {
        $this->searchString = $searchString;
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        $query = new Query();
        $query = $this->addFulltextSearchToQuery($query);
        $query->setSource(['search-result-data']);

        return $query;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Query
     */
    protected function addFulltextSearchToQuery(Query $query)
    {
        $query->setQuery(
            (new Query\Match())->setField('full-text', $this->searchString)
        );

        return $query;
    }

}
