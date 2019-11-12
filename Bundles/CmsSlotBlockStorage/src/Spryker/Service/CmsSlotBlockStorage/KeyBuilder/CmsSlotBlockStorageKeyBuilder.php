<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CmsSlotBlockStorage\KeyBuilder;

class CmsSlotBlockStorageKeyBuilder implements CmsSlotBlockStorageKeyBuilderInterface
{
    protected const FORMAT_CMS_SLOT_BLOCK_STORAGE_KEY = '%s:%s';

    /**
     * @param string $cmsSlotTemplatePath
     * @param string $cmsSlotKey
     *
     * @return string
     */
    public function generateKey(string $cmsSlotTemplatePath, string $cmsSlotKey): string
    {
        return sprintf(
            static::FORMAT_CMS_SLOT_BLOCK_STORAGE_KEY,
            $cmsSlotTemplatePath,
            $cmsSlotKey
        );
    }
}
