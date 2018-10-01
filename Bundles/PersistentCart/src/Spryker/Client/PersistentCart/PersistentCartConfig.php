<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\PersistentCart\PersistentCartConstants;

class PersistentCartConfig extends AbstractBundleConfig
{
    protected const DEFAULT_PERSISTENT_CART_ANONYMOUS_PREFIX = 'anonymous';

    /**
     * Specification:
     *  - Returns persistent cart anonymous prefix.
     *
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
