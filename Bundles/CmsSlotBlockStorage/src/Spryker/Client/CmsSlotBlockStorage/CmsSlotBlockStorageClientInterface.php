<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockStorage;

use Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer;

interface CmsSlotBlockStorageClientInterface
{
    /**
     * Specification:
     * - Finds a CMS slot block within storage with a given concrete CMS slot template path and CMS slot key.
     * - Returns null if a CMS slot block was not found.
     *
     * @api
     *
     * @param string $cmsSlotTemplatePath
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer|null
     */
    public function findCmsSlotBlockStorageData(
        string $cmsSlotTemplatePath,
        string $cmsSlotKey
    ): ?CmsSlotBlockStorageDataTransfer;
}
