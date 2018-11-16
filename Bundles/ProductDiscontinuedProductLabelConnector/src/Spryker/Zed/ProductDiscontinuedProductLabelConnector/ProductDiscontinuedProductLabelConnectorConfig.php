<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductDiscontinuedProductLabelConnectorConfig extends AbstractBundleConfig
{
    protected const PRODUCT_DISCONTINUE_LABEL_NAME = 'Discontinued';
    protected const PRODUCT_DISCONTINUE_LABEL_FRONT_END_REFERENCE = 'discontinued';

    /**
     * @return string
     */
    public function getProductDiscontinueLabelName(): string
    {
        return static::PRODUCT_DISCONTINUE_LABEL_NAME;
    }

    /**
     * @return string
     */
    public function getProductDiscontinueLabelFrontEndReference(): string
    {
        return static::PRODUCT_DISCONTINUE_LABEL_FRONT_END_REFERENCE;
    }
}
