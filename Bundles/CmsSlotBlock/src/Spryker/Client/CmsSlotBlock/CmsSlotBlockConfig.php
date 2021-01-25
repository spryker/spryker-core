<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlock;

use Spryker\Client\Kernel\AbstractBundleConfig;

class CmsSlotBlockConfig extends AbstractBundleConfig
{
    protected const IS_CMS_BLOCK_VISIBLE_IN_SLOT_BY_DEFAULT = true;

    /**
     * @api
     *
     * @return bool
     */
    public function getIsCmsBlockVisibleInSlotByDefault(): bool
    {
        return static::IS_CMS_BLOCK_VISIBLE_IN_SLOT_BY_DEFAULT;
    }
}
