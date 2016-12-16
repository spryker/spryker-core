<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Suggest\Completion;
use Generated\Shared\Search\PageIndexMap;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class CompletionQueryExpanderPlugin extends AbstractSuggestionExpanderPlugin
{

    const AGGREGATION_NAME = 'completion';

    const SIZE = 1;

    /**
     * @param \Elastica\Query $searchQuery
     * @param array $requestParameters
     *
     * @return \Elastica\Suggest\Completion
     */
    protected function createCompletion(Query $searchQuery, array $requestParameters = [])
    {
        $completion = new Completion(static::AGGREGATION_NAME, PageIndexMap::COMPLETION_TERMS);
        $completion->setSize(static::SIZE);

        return $completion;
    }

}
