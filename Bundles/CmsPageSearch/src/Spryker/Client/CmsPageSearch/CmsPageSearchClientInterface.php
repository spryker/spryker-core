<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch;

interface CmsPageSearchClientInterface
{
    /**
     * Specification:
     * - Resolves the query plugin for the given search based on plugins from {@link CmsPageSearchDependencyProvider::PLUGINS_SEARCH_QUERY}, fallbacks to the plugin from {@link CmsPageSearchDependencyProvider::PLUGIN_CMS_PAGE_SEARCH_QUERY}.
     * - A query based on the given search string and request parameters will be executed.
     * - The query will also create facet aggregations, pagination and sorting based on the request parameters.
     * - The result is a formatted associative array where the used result formatters' name are the keys and their results are the values.
     *
     * @api
     *
     * @param string $searchString
     * @param array<string, mixed> $requestParameters
     *
     * @return array
     */
    public function search(string $searchString, array $requestParameters): array;

    /**
     * Specification:
     * - Resolves the query plugin for the given search based on plugins from {@link CmsPageSearchDependencyProvider::PLUGINS_SEARCH_RESULT_COUNT}, fallbacks to the plugin from {@link CmsPageSearchDependencyProvider::PLUGIN_CMS_PAGE_SEARCH_QUERY}.
     * - A query based on the given search string and request parameters will be executed.
     * - The result is a number of hits.
     *
     * @api
     *
     * @param string $searchString
     * @param array<string, mixed> $requestParameters
     *
     * @return int
     */
    public function searchCount(string $searchString, array $requestParameters): int;
}
