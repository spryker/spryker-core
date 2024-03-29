<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CartConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const OPERATION_ADD = 'add';

    /**
     * @var string
     */
    public const OPERATION_REMOVE = 'remove';
}
