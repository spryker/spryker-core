<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsSlotBlock;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class CmsSlotBlockConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Content provider type used for CMS slot rendering.
     *
     * @api
     */
    public const CMS_SLOT_CONTENT_PROVIDER_TYPE = 'SprykerCmsSlotBlock';
}
