<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Business\CmsBlock;

interface CmsBlockFeatureDetectorInterface
{
    /**
     * @return bool
     */
    public function isCmsBlockKeyPresent(): bool;
}
