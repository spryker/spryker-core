<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Business\CmsBlock;

class CmsBlockFeatureDetector implements CmsBlockFeatureDetectorInterface
{
    /**
     * @return bool
     */
    public function isCmsBlockKeyPresent(): bool
    {
        return defined('\Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap::COL_KEY');
    }
}
