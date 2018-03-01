<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\NavigationStorage;

use Spryker\Zed\NavigationStorage\NavigationStorageConfig;

class NavigationStorageConfigMock extends NavigationStorageConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue()
    {
        return false;
    }
}
