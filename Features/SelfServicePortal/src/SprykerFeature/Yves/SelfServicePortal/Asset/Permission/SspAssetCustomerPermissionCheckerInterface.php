<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Permission;

interface SspAssetCustomerPermissionCheckerInterface
{
    /**
     * @return bool
     */
    public function canViewAsset(): bool;
}
