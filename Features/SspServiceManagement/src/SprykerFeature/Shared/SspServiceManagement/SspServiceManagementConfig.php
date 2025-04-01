<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SspServiceManagement;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SspServiceManagementConfig extends AbstractBundleConfig
{
    /**
     * Specification
     * - Constant is used to group abstract type product-related product page data expanders.
     *
     * @api
     *
     * @var string
     */
    public const PLUGIN_PRODUCT_ABSTRACT_TYPE_DATA = 'PLUGIN_PRODUCT_ABSTRACT_TYPE_DATA';

    /**
     * Specification:
     * - Returns the product service type name.
     *
     * @api
     *
     * @return string
     */
    public function getProductServiveTypeName(): string
    {
        return 'service';
    }
}
