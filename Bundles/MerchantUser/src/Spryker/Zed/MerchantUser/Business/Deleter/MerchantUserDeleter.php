<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Deleter;

use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;

class MerchantUserDeleter implements MerchantUserDeleterInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     */
    public function __construct(MerchantUserToUserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function delete(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer
    {
        $merchantUserTransfer->requireIdUser();

        $userTransfer = $this->userFacade->removeUser(
            $merchantUserTransfer->getIdUserOrFail()
        );
        $merchantUserTransfer->setUser($userTransfer);

        return (new MerchantUserResponseTransfer())
            ->setIsSuccessful(true)
            ->setMerchantUser($merchantUserTransfer);
    }
}
