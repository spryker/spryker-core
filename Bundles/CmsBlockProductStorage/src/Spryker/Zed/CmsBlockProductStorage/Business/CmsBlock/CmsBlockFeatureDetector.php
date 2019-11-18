<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Business\CmsBlock;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;

class CmsBlockFeatureDetector implements CmsBlockFeatureDetectorInterface
{
    /**
     * @return bool
     */
    public function isCmsBlockKeyPresent(): bool
    {
        return defined(SpyCmsBlockTableMap::class . '::COL_KEY');
    }
}
