<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Permission;

use Generated\Shared\Transfer\SspServiceCriteriaTransfer;

interface SspServiceCustomerPermissionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
    public function expand(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCriteriaTransfer;
}
