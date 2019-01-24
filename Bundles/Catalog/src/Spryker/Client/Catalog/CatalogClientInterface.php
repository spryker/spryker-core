<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * Specification:
     *  - Reads current view mode as store in cookie, the view mode is listing mode for catalog.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getCatalogViewMode(Request $request);

    /**
     * Specification:
     *  - Stores current catalog view mode to cookie.
     *  - Updates Response object with cookie information.
     *
     * @api
     *
     * @param string $mode
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setCatalogViewMode($mode, Response $response);

    /**
     * Specification:
     * - A query based on the given search string and request parameters will be executed
     * - The result is a number of hits
     *
     * @api
     *
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return int
     */
    public function catalogSearchCount(string $searchString, array $requestParameters): int;

    /**
     * Specification:
     * - Finds concrete products at Elasticsearch by full-text.
     * - Filters results by searchString and locale.
     * - Limit and offset can be specified.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array|\Elastica\ResultSet
     */
    public function searchProductConcretesByFullText(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer);
}
