<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductPageSearch;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ProductPageSearchConfig extends AbstractSharedConfig
{
    /**
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_MODES
     *
     * @var array
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
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_PAGE_LOAD_DATA = 'PRODUCT_ABSTRACT_PAGE_LOAD_DATA';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_PRICE_PAGE_DATA = 'PLUGIN_PRODUCT_PRICE_PAGE_DATA';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_CATEGORY_PAGE_DATA = 'PLUGIN_PRODUCT_CATEGORY_PAGE_DATA';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_IMAGE_PAGE_DATA = 'PLUGIN_PRODUCT_IMAGE_PAGE_DATA';

    /**
     * Specification:
     *  - Default Price Dimension name.
     *
     * @uses \Spryker\Shared\PriceProductStorage\PriceProductStorageConstants::PRICE_DIMENSION_DEFAULT
     *
     * @var string
     */
    public const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * Defines queue name for publish.
     *
     * @var string
     */
    public const PUBLISH_PRODUCT_ABSTRACT_PAGE = 'publish.page_product_abstract';

    /**
     * Defines queue name for publish.
     *
     * @var string
     */
    public const PUBLISH_PRODUCT_CONCRETE_PAGE = 'publish.page_product_concrete';
}
