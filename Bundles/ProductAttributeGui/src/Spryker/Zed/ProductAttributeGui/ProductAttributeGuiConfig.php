<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAttributeGuiConfig extends AbstractBundleConfig
{
    public const DEFAULT_LOCALE = '_';

    public const KEY = 'key';
    public const IS_SUPER = 'is_super';
    public const ATTRIBUTE_ID = 'attribute_id';
    public const ALLOW_INPUT = 'allow_input';
    public const INPUT_TYPE = 'input_type';
    public const ID_PRODUCT_ATTRIBUTE_KEY = 'id_product_attribute_key';
    public const LOCALE_CODE = 'locale_code';

    /**
     * @return string
     */
    public function getDefaultLocaleCode()
    {
        return static::DEFAULT_LOCALE;
    }
}
