<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductOptionStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductOptionStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const PRODUCT_ABSTRACT_OPTION_RESOURCE_NAME = 'product_abstract_option';
}
