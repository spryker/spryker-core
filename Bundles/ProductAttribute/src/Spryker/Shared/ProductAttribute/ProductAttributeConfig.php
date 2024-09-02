<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductAttribute;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductAttributeConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const PRODUCT_ATTRIBUTE_GLOSSARY_PREFIX = 'product.attribute.';

    /**
     * Specification:
     * - Defines the multiselect input type for the product attribute.
     *
     * @api
     *
     * @var string
     */
    public const INPUT_TYPE_MULTISELECT = 'multiselect';
}
