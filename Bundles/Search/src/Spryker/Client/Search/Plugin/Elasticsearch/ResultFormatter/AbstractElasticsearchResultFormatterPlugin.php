<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Plugin\ResultFormatterPluginInterface;

abstract class AbstractElasticsearchResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    public function formatResult($searchResult, array $requestParameters = [])
    {
        $this->assertResultType($searchResult);

        return $this->formatSearchResult($searchResult, $requestParameters);
    }

    /**
     * @param mixed $searchResult
     *
     * @return void
     */
    protected function assertResultType($searchResult)
    {
        if (!$searchResult instanceof ResultSet) {
            throw new \InvalidArgumentException(sprintf(
                'Expected search result type was "%s", got "%s" instead.',
                ResultSet::class,
                get_class($searchResult)
            ));
        }
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    abstract protected function formatSearchResult(ResultSet $searchResult, array $requestParameters);

}
