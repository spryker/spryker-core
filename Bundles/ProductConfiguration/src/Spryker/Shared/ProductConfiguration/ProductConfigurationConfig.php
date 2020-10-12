<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductConfiguration;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductConfigurationConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Dimension type as used for product configuration price.
     *
     * @api
     */
    public const PRICE_DIMENSION_PRODUCT_CONFIGURATION = 'PRODUCT_CONFIGURATION';

    /**
     * Specification:
     * - Indicate source type as pdp.
     *
     * @api
     */
    public const SOURCE_TYPE_PDP = 'SOURCE_TYPE_PDP';

    /**
     * Specification:
     * - Indicate source type as cart.
     *
     * @api
     */
    public const SOURCE_TYPE_CART = 'SOURCE_TYPE_CART';

    /**
     * Specification:
     * - Provides encryption key for checksum validation.
     *
     * @api
     */
    public const SPRYKER_CONFIGURATOR_ENCRYPTION_KEY = 'SPRYKER_CONFIGURATOR_ENCRYPTION_KEY';
}
