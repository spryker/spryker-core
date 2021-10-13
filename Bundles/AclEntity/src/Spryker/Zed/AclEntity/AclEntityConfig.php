<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity;

use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AclEntityConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return int
     */
    public function getDefaultGlobalOperationMask(): int
    {
        return 0;
    }

    /**
     * @api
     *
     * @return array<int>
     */
    public function getScopePriority(): array
    {
        return [
            AclEntityConstants::SCOPE_GLOBAL => 2,
            AclEntityConstants::SCOPE_INHERITED => 1,
            AclEntityConstants::SCOPE_SEGMENT => 0,
        ];
    }
}
