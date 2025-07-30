<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business\MerchantFile\Expander;

use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Zed\MerchantFile\Dependency\Facade\MerchantFileToMerchantUserInterface;

class MerchantFileExpander implements MerchantFileExpanderInterface
{
    /**
     * @param \Spryker\Zed\MerchantFile\Dependency\Facade\MerchantFileToMerchantUserInterface $merchantUserFacade
     */
    public function __construct(protected MerchantFileToMerchantUserInterface $merchantUserFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    public function expandWithMerchantUser(
        MerchantFileTransfer $merchantFileTransfer
    ): MerchantFileTransfer {
        $currentMerchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
        $merchantFileTransfer->setFkMerchant($currentMerchantUserTransfer->getIdMerchant());
        $merchantFileTransfer->setFkUser($currentMerchantUserTransfer->getIdUser());
        $merchantFileTransfer->setUser($currentMerchantUserTransfer->getUser());

        return $merchantFileTransfer;
    }
}
