<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesReturnGuiConfig extends AbstractBundleConfig
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
