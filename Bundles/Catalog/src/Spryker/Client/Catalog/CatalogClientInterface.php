<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

interface CatalogClientInterface
{
    /**
     * Specification:
     * - A query based on the given search string and request parameters will be executed
     * - The query will also create facet aggregations, pagination and sorting based on the request parameters
     * - The result is a formatted associative array where the used result formatters' name are the keys and their results are the values
     *
     * @api
     *
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function catalogSearch($searchString, array $requestParameters);

    /**
     * Specification:
     * - A query based on the given search string and request parameters will be executed
     * - The query will be extended with the provided plugins via `\Spryker\Client\Catalog\CatalogDependencyProvider::SUGGESTION_QUERY_EXPANDER_PLUGINS`.
     * - The result will be formatted with the provided plugins via `\Spryker\Client\Catalog\CatalogDependencyProvider::SUGGESTION_RESULT_FORMATTER_PLUGINS`.
     * - The result is a formatted associative array where the provided result formatters' name are the keys and their results are the values.
     *
     * @api
     *
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function catalogSuggestSearch($searchString, array $requestParameters = []);
}
