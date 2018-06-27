<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductDiscontinuedProductLabelConnectorConfig extends AbstractBundleConfig
{
    protected const PRODUCT_DISCONTINUE_LABEL_KEY = 'product.discontinued.product.label.name';

    protected const PRODUCT_DISCONTINUE_LABEL_NAME = 'Discontinued';

    /**
     * @return string
     */
    public function getProductDiscontinueLabelKey(): string
    {
        return static::PRODUCT_DISCONTINUE_LABEL_KEY;
    }

    /**
     * @return string
     */
    public function getProductDiscontinueLabelName(): string
    {
        return static::PRODUCT_DISCONTINUE_LABEL_NAME;
    }
}
