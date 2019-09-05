<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter;

use Elastica\ResultSet;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchElasticsearch\Exception\InvalidSearchResultTypeException;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

abstract class AbstractElasticsearchResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{
    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return mixed
     */
    public function formatResult($searchResult, array $requestParameters = [])
    {
        $this->assertResultType($searchResult);

        return $this->formatSearchResult($searchResult, $requestParameters);
    }

    /**
     * @param mixed $searchResult
     *
     * @throws \Spryker\Client\SearchElasticsearch\Exception\InvalidSearchResultTypeException
     *
     * @return void
     */
    protected function assertResultType($searchResult): void
    {
        if (!$searchResult instanceof ResultSet) {
            throw new InvalidSearchResultTypeException(sprintf(
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
     * @return mixed
     */
    abstract protected function formatSearchResult(ResultSet $searchResult, array $requestParameters);
}
