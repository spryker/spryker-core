<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\PersistentCart\PersistentCartConfig getSharedConfig()
 */
class PersistentCartConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getPersistentCartAnonymousPrefix(): string
    {
        return $this->getSharedConfig()->getPersistentCartAnonymousPrefix();
    }
}
