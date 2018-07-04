<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsPageSearch;

use Spryker\Zed\CmsPageSearch\CmsPageSearchConfig;

class CmsPageSearchConfigMock extends CmsPageSearchConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return false;
    }
}
