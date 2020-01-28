<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductMeasurementUnitsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PRODUCT_MEASUREMENT_UNITS = 'product-measurement-units';
    public const RESOURCE_SALES_UNITS = 'sales-units';

    public const CONTROLLER_PRODUCT_MEASUREMENT_UNITS = 'product-measurement-units-resource';
    public const CONTROLLER_SALES_UNITS = 'sales-units-resource';
}
