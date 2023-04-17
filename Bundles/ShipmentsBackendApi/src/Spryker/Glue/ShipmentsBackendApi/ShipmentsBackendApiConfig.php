<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsBackendApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ShipmentsBackendApiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource type for sales shipments.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_SALES_SHIPMENTS = 'sales-shipments';
}
