<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\SortSearchResultTransfer;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

/**
 * @method \Spryker\Client\CmsPageSearch\CmsPageSearchFactory getFactory()
 */
class SortedCmsPageSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'sort';

    /**
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
     * @return \Generated\Shared\Transfer\SortSearchResultTransfer
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): SortSearchResultTransfer
    {
        $sortConfig = $this
            ->getFactory()
            ->createSortConfigBuilder();

        $sortParamName = $sortConfig->getActiveParamName($requestParameters);

        $sortSearchResultTransfer = (new SortSearchResultTransfer())
            ->setSortParamNames(array_keys($sortConfig->getAllSortConfigTransfers()))
            ->setCurrentSortParam($sortParamName)
            ->setCurrentSortOrder($sortConfig->getSortDirection($sortParamName));

        return $sortSearchResultTransfer;
    }
}
