<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Dependency\Facade;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface;

class MerchantFileToMerchantUserBridge implements MerchantFileToMerchantUserInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface
     */
    protected MerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct($merchantUserFacade)
    {
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function getCurrentMerchantUser(): MerchantUserTransfer
    {
        return $this->merchantUserFacade->getCurrentMerchantUser();
    }
}
