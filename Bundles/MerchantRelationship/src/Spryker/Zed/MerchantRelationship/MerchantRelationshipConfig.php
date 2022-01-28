<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantRelationshipConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return int
     */
    public function getDefaultPaginationLimit(): int
    {
        return 20;
    }
}
