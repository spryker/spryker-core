<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission;

use Generated\Shared\Transfer\SspAssetCriteriaTransfer;

interface SspAssetCustomerPermissionExpanderInterface
{
    public function expandCriteria(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspAssetCriteriaTransfer;
}
