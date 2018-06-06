<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockProductStorage;

use Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageConfig;

class CmsBlockProductStorageConfigMock extends CmsBlockProductStorageConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue()
    {
        return false;
    }
}
