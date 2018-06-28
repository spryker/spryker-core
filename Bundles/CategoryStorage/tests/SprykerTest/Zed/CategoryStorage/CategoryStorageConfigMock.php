<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryStorage;

use Spryker\Zed\CategoryStorage\CategoryStorageConfig;

class CategoryStorageConfigMock extends CategoryStorageConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue()
    {
        return false;
    }
}
