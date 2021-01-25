<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Security;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Symfony\Component\Security\Core\User\UserInterface;

interface MerchantUserInterface extends UserInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function getMerchantUserTransfer(): MerchantUserTransfer;
}
