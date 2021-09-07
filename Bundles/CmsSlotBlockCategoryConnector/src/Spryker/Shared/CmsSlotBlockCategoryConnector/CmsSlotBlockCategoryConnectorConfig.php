<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsSlotBlockCategoryConnector;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CmsSlotBlockCategoryConnectorConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines the key for visibility condition CMS Block in Slot.
     *
     * @api
     * @var string
     */
    public const CONDITION_KEY = 'category';
}
