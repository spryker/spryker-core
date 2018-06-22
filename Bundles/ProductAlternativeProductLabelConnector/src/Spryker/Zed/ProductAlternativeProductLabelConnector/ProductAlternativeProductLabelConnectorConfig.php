<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAlternativeProductLabelConnectorConfig extends AbstractBundleConfig
{
    protected const PRODUCT_ALTERNATIVES_LABEL = 'Alternatives available';

    /**
     * @return string
     */
    public function getProductAlternativesLabel(): string
    {
        return static::PRODUCT_ALTERNATIVES_LABEL;
    }
}
