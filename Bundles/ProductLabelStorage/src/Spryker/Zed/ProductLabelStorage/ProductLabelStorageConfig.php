<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductLabelStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue()
    {
        return true;
    }

    /**
     * @return null|string
     */
    public function getProductAbstractLabelSynchronizationPoolName()
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function getProductLabelDictionarySynchronizationPoolName()
    {
        return null;
    }
}
