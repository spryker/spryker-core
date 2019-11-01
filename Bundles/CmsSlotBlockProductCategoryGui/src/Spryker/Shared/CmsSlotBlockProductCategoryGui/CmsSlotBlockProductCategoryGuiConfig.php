<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsSlotBlockProductCategoryGui;

use Spryker\Shared\Kernel\AbstractBundleConfig;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class CmsSlotBlockProductCategoryGuiConfig extends AbstractBundleConfig
{
    public const CONDITION_KEY = 'productCategory';

    /**
     * Specification:
     * - Defines the key for CMS block condition.
     *
     * @api
     *
     * @return string
     */
    public function getConditionKey(): string
    {
        return static::CONDITION_KEY;
    }
}
