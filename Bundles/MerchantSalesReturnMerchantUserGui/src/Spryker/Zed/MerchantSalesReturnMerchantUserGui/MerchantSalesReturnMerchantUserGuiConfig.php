<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantSalesReturnMerchantUserGuiConfig extends AbstractBundleConfig
{
    protected const ITEM_STATE_TO_LABEL_CLASS_MAPPING = [
        'refunded' => 'label-danger',
        'closed' => 'label-inverse',
        'shipped to customer' => 'label-success',
    ];

    /**
     * @api
     *
     * @return string[]
     */
    public function getItemStateToLabelClassMapping(): array
    {
        return static::ITEM_STATE_TO_LABEL_CLASS_MAPPING;
    }
}
