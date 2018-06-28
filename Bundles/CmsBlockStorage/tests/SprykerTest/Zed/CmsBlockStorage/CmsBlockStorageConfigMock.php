<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockStorage;

use Spryker\Zed\CmsBlockStorage\CmsBlockStorageConfig;

class CmsBlockStorageConfigMock extends CmsBlockStorageConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue()
    {
        return false;
    }
}
