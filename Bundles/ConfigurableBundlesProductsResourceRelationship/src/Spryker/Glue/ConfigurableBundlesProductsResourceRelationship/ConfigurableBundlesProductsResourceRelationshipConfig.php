<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesProductsResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ConfigurableBundlesProductsResourceRelationshipConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS
     */
    public const RESOURCE_CONCRETE_PRODUCTS = 'concrete-products';

    /**
     * @uses \Spryker\Glue\ConfigurableBundlesRestApi\ConfigurableBundlesRestApiConfig::RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOTS
     */
    public const RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOTS = 'configurable-bundle-template-slots';
}
