<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockStorage\Storage;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;

interface CmsSlotBlockStorageReaderInterface
{
    /**
     * @param string $cmsSlotTemplatePath
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollection(
        string $cmsSlotTemplatePath,
        string $cmsSlotKey
    ): CmsSlotBlockCollectionTransfer;
}
