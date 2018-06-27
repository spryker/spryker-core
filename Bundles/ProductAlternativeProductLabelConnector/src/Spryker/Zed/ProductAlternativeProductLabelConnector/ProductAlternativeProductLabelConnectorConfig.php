<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAlternativeProductLabelConnectorConfig extends AbstractBundleConfig
{
    protected const PRODUCT_ALTERNATIVES_LABEL_KEY = 'product.alternative.product.label.name';

    protected const PRODUCT_ALTERNATIVES_LABEL_NAME = 'Alternatives available';

    /**
     * @return string
     */
    public function getProductAlternativesLabelKey(): string
    {
        return static::PRODUCT_ALTERNATIVES_LABEL_KEY;
    }

    /**
     * @return string
     */
    public function getProductAlternativesLabelName(): string
    {
        return static::PRODUCT_ALTERNATIVES_LABEL_NAME;
    }
}
