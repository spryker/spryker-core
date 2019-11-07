<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Business\Storage;

interface CmsSlotBlockStorageWriterInterface
{
    /**
     * @param string[] $cmsSlotBlockIds
     *
     * @return void
     */
    public function publish(array $cmsSlotBlockIds): void;
}
