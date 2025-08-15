<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductImage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductImageConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const DEFAULT_IMAGE_SET_NAME = 'default';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_IMAGE_ALTERNATIVE_TEXT_DATA = 'PLUGIN_PRODUCT_IMAGE_ALTERNATIVE_TEXT_DATA';

    /**
     * @api
     *
     * @deprecated This method will be removed in the next major version. Product image alternative text will be enabled by default.
     *
     * Specification:
     * - Enable or disable product image alternative text feature.
     *
     * @return bool
     */
    public function isProductImageAlternativeTextEnabled(): bool
    {
        return false;
    }
}
