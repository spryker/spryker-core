<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductNew;

/**
 * @method \Spryker\Client\ProductNew\ProductNewFactory getFactory()
 */
interface ProductNewClientInterface
{
    /**
     * Specification:
     * - A query based on the request parameters will be executed.
     * - The result contains only products that has the label assigned based on configuration.
     * - The query will also create facet aggregations, pagination and sorting based on the request parameters
     * - The result is a formatted associative array where the used result formatters' name are the keys and their results are the values
     *
     * @api
     *
     * @param array $requestParameters
     *
     * @return array
     */
    public function findNewProducts(array $requestParameters);
}
