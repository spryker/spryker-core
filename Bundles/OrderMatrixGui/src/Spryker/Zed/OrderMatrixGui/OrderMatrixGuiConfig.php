<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrixGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OrderMatrixGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const SALES_URL_PLACEHOLDER = '/sales?id-order-item-process=%s&id-order-item-state=%s&filter=%s';

    /**
     * Specification:
     * - Returns the placeholder for the sales URL.
     *
     * @api
     *
     * @return string
     */
    public function getSalesUrlPlaceholder(): string
    {
        return static::SALES_URL_PLACEHOLDER;
    }
}
