<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Handler;

use Elastica\SearchableInterface;
use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Model\ResultFormatter\ResultFormatterInterface;

// FIXME: try Tobi's suggestion
class ElasticsearchSearchHandler implements SearchHandlerInterface
{

    /**
     * @var \Elastica\SearchableInterface
     */
    protected $searchableInterface;

    /**
     * @param \Elastica\SearchableInterface $searchableInterface
     */
    public function __construct(SearchableInterface $searchableInterface)
    {
        $this->searchableInterface = $searchableInterface;
    }

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Model\ResultFormatter\ResultFormatterInterface $resultFormatter
     *
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, ResultFormatterInterface $resultFormatter)
    {
        $query = $searchQuery->getSearchQuery();
        $rawSearchResult = $this->searchableInterface->search($query);
        $formattedSearchResult = $resultFormatter->formatResult($rawSearchResult);

        return $formattedSearchResult;
    }

}
