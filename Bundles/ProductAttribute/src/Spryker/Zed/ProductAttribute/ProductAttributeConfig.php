<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAttributeConfig extends AbstractBundleConfig
{

    const DEFAULT_LOCALE = '_';
    const STORE_PATTERN_MARKER = '{STORE}';

    const KEY = 'key';
    const IS_SUPER = 'is_super';
    const ATTRIBUTE_ID = 'attribute_id';
    const ALLOW_INPUT = 'allow_input';
    const INPUT_TYPE = 'input_type';
    const ID_PRODUCT_ATTRIBUTE_KEY = 'id_product_attribute_key';
    const LOCALE_CODE = 'locale_code';

    /**
     * @return string
     */
    public function getCachePath()
    {
        return APPLICATION_DATA . '/' . static::STORE_PATTERN_MARKER . '/cache';
    }

    /**
     * @return string
     */
    public function getAutoloaderCachePath()
    {
        return APPLICATION_DATA . '/' . static::STORE_PATTERN_MARKER . '/autoloader';
    }

    /**
     * @return string
     */
    public function getStorePatternMarker()
    {
        return static::STORE_PATTERN_MARKER;
    }

    /**
     * @return array
     */
    public function getAllowedStores()
    {
        return Store::getInstance()->getAllowedStores();
    }

}
