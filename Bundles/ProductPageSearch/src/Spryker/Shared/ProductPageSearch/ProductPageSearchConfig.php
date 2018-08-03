<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductPageSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductPageSearchConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_MODES
     */
    public const PRICE_MODES = [
        'NET_MODE',
        'GROSS_MODE',
    ];

    /**
     * Specification:
     * - This constant is used for extracting data from plugin array
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_PAGE_LOAD_DATA = 'PRODUCT_ABSTRACT_PAGE_LOAD_DATA';
}
