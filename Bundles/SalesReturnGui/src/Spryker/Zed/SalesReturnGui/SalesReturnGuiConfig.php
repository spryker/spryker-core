<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesReturnGuiConfig extends AbstractBundleConfig
{
    public const ITEM_STATE_TO_LABEL_CLASS_MAPPING = [
        'returned' => 'label-inverse',
        'refunded' => 'label-info',
        'closed' => 'label-danger',
    ];
}
