<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductLabelStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductLabelStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const PRODUCT_ABSTRACT_LABEL_RESOURCE_NAME = 'product_abstract_label';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const PRODUCT_LABEL_DICTIONARY_RESOURCE_NAME = 'product_label_dictionary';
}
