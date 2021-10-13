<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CartCode;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CartCodesConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const MESSAGE_TYPE_SUCCESS = 'success';
    /**
     * @var string
     */
    public const MESSAGE_TYPE_ERROR = 'error';
}
