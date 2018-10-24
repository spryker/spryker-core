<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart;

use Spryker\Shared\PersistentCart\PersistentCartConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PersistentCartConfig extends AbstractBundleConfig
{
    protected const DEFAULT_PERSISTENT_CART_ANONYMOUS_PREFIX = 'anonymous:';

    /**
     * @return string
     */
    public function getPersistentCartAnonymousPrefix(): string
    {
        return $this->get(
            PersistentCartConstants::PERSISTENT_CART_ANONYMOUS_PREFIX,
            static::DEFAULT_PERSISTENT_CART_ANONYMOUS_PREFIX
        );
    }
}
