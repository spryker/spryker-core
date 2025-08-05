<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Permission;

use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;

class SspAssetCustomerPermissionChecker implements SspAssetCustomerPermissionCheckerInterface
{
    use PermissionAwareTrait;

    public function canViewAsset(): bool
    {
        $canViewCompanySspAssets = $this->can(ViewCompanySspAssetPermissionPlugin::KEY); // THIS IS Ok (fail fast) ZED mirror created

        $canViewBusinessUnitSspAssets = $this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY); // THIS IS Ok (fail fast) ZED mirror created

        return $canViewCompanySspAssets || $canViewBusinessUnitSspAssets;
    }
}
