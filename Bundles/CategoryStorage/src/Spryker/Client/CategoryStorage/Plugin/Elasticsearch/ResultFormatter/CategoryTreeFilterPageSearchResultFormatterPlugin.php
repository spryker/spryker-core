<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Plugin\Elasticsearch\ResultFormatter;

use ArrayObject;
use Elastica\ResultSet;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

/**
 * @method \Spryker\Client\CategoryStorage\CategoryStorageClient getClient()
 * @method \Spryker\Client\CategoryStorage\CategoryStorageFactory getFactory()
 */
class CategoryTreeFilterPageSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    protected const NAME = 'categoryTreeFilter';

    /**
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): ArrayObject
    {
        $name = $this->getFactory()->getConfig()->getCategoryFacetAggregationName();
        $docCountAggregation = $searchResult->getAggregations()[$name] ?? [];

        return $this->getClient()->formatCategoryTreeFilter($docCountAggregation);
    }
}
