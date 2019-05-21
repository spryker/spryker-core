<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAlternativeProductLabelConnectorConfig extends AbstractBundleConfig
{
    protected const PRODUCT_ALTERNATIVES_LABEL_NAME = 'Alternatives available';
    protected const PRODUCT_ALTERNATIVES_LABEL_FRONT_END_REFERENCE = 'alternatives';

    /**
     * Specification:
     * - Returns product alternatives label.
     *
     * @api
     *
     * @return string
     */
    public function getProductAlternativesLabelName(): string
    {
        return static::PRODUCT_ALTERNATIVES_LABEL_NAME;
    }

    /**
     * Specification:
     * - Returns frontend reference of product alternatives label.
     *
     * @api
     *
     * @return string
     */
    public function getProductAlternativesLabelFrontEndReference(): string
    {
        return static::PRODUCT_ALTERNATIVES_LABEL_FRONT_END_REFERENCE;
    }
}
