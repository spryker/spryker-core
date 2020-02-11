<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsSlotBlockProductCategoryConnector;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CmsSlotBlockProductCategoryConnectorConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines the key for visibility condition CMS Block in Slot.
     *
     * @api
     */
    public const CONDITION_KEY = 'productCategory';
}
