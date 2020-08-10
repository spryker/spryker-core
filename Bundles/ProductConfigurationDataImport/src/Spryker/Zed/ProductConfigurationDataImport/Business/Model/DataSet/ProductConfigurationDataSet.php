<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationDataImport\Business\Model\DataSet;

interface ProductConfigurationDataSet
{
    public const KEY_CONCRETE_SKU = 'concrete_sku';
    public const KEY_CONFIGURATION_KEY = 'configurator_key';
    public const KEY_IS_COMPLETE = 'is_complete';
    public const KEY_DEFAULT_CONFIGURATION = 'default_configuration';
    public const KEY_DEFAULT_DISPLAY_DATA = 'default_display_data';

    public const ID_PRODUCT_CONCRETE = 'id_product_concrete';
}
