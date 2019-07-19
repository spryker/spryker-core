<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Search;

use Elastica\ResultSet;
use Spryker\Client\Search\Exception\SearchDelegatorException;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @TODO can be removed when refactored to ClientAdapterPlugins.
 */
class SearchDelegator implements SearchInterface
{
    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\SearchPluginInterface[]
     */
    protected $searchPlugins;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchPluginInterface[] $searchPlugins
     */
    public function __construct(array $searchPlugins)
    {
        $this->searchPlugins = $searchPlugins;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $resultFormatters
     * @param array $requestParameters
     *
     * @throws \Spryker\Client\Search\Exception\SearchDelegatorException
     *
     * @return \Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): ResultSet
    {
        foreach ($this->searchPlugins as $searchPlugin) {
            if ($searchPlugin->accept($searchQuery)) {
                return $searchPlugin->search($searchQuery, $resultFormatters, $requestParameters);
            }
        }

        throw new SearchDelegatorException('None of the attached SearchPlugins is able to handle the incoming query.');
    }
}
