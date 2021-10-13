<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAttributeGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const DEFAULT_LOCALE = '_';

    /**
     * @var string
     */
    public const KEY = 'key';
    /**
     * @var string
     */
    public const IS_SUPER = 'is_super';
    /**
     * @var string
     */
    public const ATTRIBUTE_ID = 'attribute_id';
    /**
     * @var string
     */
    public const ALLOW_INPUT = 'allow_input';
    /**
     * @var string
     */
    public const INPUT_TYPE = 'input_type';
    /**
     * @var string
     */
    public const ID_PRODUCT_ATTRIBUTE_KEY = 'id_product_attribute_key';
    /**
     * @var string
     */
    public const LOCALE_CODE = 'locale_code';

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultLocaleCode()
    {
        return static::DEFAULT_LOCALE;
    }
}
