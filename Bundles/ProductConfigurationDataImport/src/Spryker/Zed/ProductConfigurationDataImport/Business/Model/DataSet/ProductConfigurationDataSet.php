<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductConfigurationDataImport\Business\Model\DataSet;

interface ProductConfigurationDataSet
{
    /**
     * @var string
     */
    public const KEY_CONCRETE_SKU = 'concrete_sku';
    /**
     * @var string
     */
    public const KEY_CONFIGURATION_KEY = 'configurator_key';
    /**
     * @var string
     */
    public const KEY_IS_COMPLETE = 'is_complete';
    /**
     * @var string
     */
    public const KEY_DEFAULT_CONFIGURATION = 'default_configuration';
    /**
     * @var string
     */
    public const KEY_DEFAULT_DISPLAY_DATA = 'default_display_data';

    /**
     * @var string
     */
    public const ID_PRODUCT_CONCRETE = 'id_product_concrete';
}
