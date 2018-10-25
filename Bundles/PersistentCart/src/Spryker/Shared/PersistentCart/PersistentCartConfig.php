<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PersistentCart;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class PersistentCartConfig extends AbstractBundleConfig
{
    /**
     * Persistent cart anonymous prefix.
     */
    protected const PERSISTENT_CART_ANONYMOUS_PREFIX = 'anonymous:';

    /**
     * @return string
     */
    public function getPersistentCartAnonymousPrefix(): string
    {
        return static::PERSISTENT_CART_ANONYMOUS_PREFIX;
    }
}
