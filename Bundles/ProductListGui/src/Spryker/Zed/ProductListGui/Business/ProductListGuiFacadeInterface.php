<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Business;

interface ProductListGuiFacadeInterface
{
    /**
     * @api
     *
     * @module Category
     *
     * @return string[] [<category id> => <category name in english locale>]
     */
    public function getAllCategoriesNames(): array;

    /**
     * @api
     *
     * @module Product
     *
     * @return string[] [<product id> => <product name in english locale>]
     */
    public function getAllProductsNames(): array;
}
