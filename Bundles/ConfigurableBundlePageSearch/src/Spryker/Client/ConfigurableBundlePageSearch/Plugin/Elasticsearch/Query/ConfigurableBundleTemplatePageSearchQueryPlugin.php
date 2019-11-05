<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundlePageSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Shared\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig;

class ConfigurableBundleTemplatePageSearchQueryPlugin extends AbstractPlugin implements QueryInterface
{
    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer
     */
    protected $configurableBundleTemplatePageSearchRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer
     */
    public function __construct(ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer)
    {
        $this->configurableBundleTemplatePageSearchRequestTransfer = $configurableBundleTemplatePageSearchRequestTransfer;
        $this->query = $this->createSearchQuery();
    }

    /**
     * @api
     *
     * @return \Elastica\Query
     */
    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery(): Query
    {
        $query = new Query();

        $this->setQuery($query)->setSource($query);

        return $query;
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return $this
     */
    protected function setQuery(Query $baseQuery)
    {
        $boolQuery = new BoolQuery();
        $this->setTypeFilter($boolQuery);

        $baseQuery->setQuery($boolQuery);

        return $this;
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     *
     * @return void
     */
    protected function setTypeFilter(BoolQuery $boolQuery): void
    {
        $typeFilter = new Match();
        $typeFilter->setField(PageIndexMap::TYPE, ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME);

        $boolQuery->addMust($typeFilter);
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return $this
     */
    protected function setSource(Query $query)
    {
        $query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);

        return $this;
    }
}
