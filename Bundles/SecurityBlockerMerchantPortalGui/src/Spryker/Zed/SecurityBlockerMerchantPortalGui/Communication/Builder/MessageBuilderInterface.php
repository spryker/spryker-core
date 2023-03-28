<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;

interface MessageBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer
     *
     * @return string
     */
    public function getExceptionMessage(SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer): string;
}
