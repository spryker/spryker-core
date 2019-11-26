<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsSlotBlockCmsConnector;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class CmsSlotBlockCmsConnectorConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Defines the key for visibility condition CMS Block in Slot.
     *
     * @api
     */
    public const CONDITION_KEY = 'cms_page';
}
