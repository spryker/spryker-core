<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroup;

interface ProductGroupClientInterface
{

    /**
     * Specification:
     * - Reads all product groups of the given product.
     * - Reads all abstract product IDs of all the obtained product groups.
     * - Reads and returns abstract product data for all the obtained abstract products.
     * - The given product won't be part of the result.
     * - All data reading is happening from Storage in the given locale.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    public function getGroupElementsByIdProductAbstract($idProductAbstract, $localeName);

}
