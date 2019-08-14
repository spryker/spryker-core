<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

/**
 * @TODO can be removed when refactored to ClientAdapterPlugins.
 */
interface SearchPluginInterface
{
    /**
     * Specification:
     * - Decides if this plugin can handle the incoming QueryInterface.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return bool
     */
    public function accept(QueryInterface $searchQuery): bool;

    /**
     * Specification:
     * - Runs the search query based on the search configuration provided by this client.
     * - If there's no result formatter given then the raw search result will be returned.
     * - The formatted search result will be an associative array where the keys are the name and the values are the formatted results.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return \Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []);
}
