<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Communication\Reader;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface
     */
    protected MerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected MerchantUserToUserFacadeInterface $userFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     */
    public function __construct(
        MerchantUserFacadeInterface $merchantUserFacade,
        MerchantUserToUserFacadeInterface $userFacade
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->userFacade = $userFacade;
    }

    /**
     * @return string
     */
    public function getCurrentUserMerchantName(): string
    {
        $merchantName = '';

        if (!$this->userFacade->hasCurrentUser()) {
            return $merchantName;
        }

        $userTransfer = $this->userFacade->getCurrentUser();

        if (!$userTransfer->getIdUser()) {
            return $merchantName;
        }

        $merchantUserTransfer = $this->merchantUserFacade->findMerchantUser(
            (new MerchantUserCriteriaTransfer())->setIdUser($userTransfer->getIdUserOrFail()),
        );

        if ($merchantUserTransfer === null) {
            return $merchantName;
        }

        return $merchantUserTransfer->getMerchantOrFail()->getName() ?? $merchantName;
    }
}
