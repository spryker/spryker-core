<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsSlotBlockConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string[][]
     */
    public function getTemplateConditionsAssignment(): array
    {
        return [];
    }
}
