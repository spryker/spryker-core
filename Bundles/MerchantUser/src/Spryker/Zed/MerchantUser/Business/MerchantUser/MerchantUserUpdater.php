<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;

class MerchantUserUpdater implements MerchantUserUpdaterInterface
{
    /**
     * @see \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     */
    protected const USER_STATUS_ACTIVE = 'active';

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface
     */
    protected $authFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface $authFacade
     */
    public function __construct(
        MerchantUserToUserFacadeInterface $userFacade,
        MerchantUserToAuthFacadeInterface $authFacade
    ) {
        $this->userFacade = $userFacade;
        $this->authFacade = $authFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function update(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer
    {
        $merchantUserTransfer->requireUser();

        $originalUserTransfer = $this->userFacade->getUserById($merchantUserTransfer->getIdUser());

        $userTransfer = $this->userFacade->updateUser($merchantUserTransfer->getUser());

        $this->resetUserPassword($originalUserTransfer, $userTransfer);

        $response = new MerchantUserResponseTransfer();

        $response->setIsSuccessful(true)->setMerchantUser($merchantUserTransfer);

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $originalUserTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $updatedUserTransfer
     *
     * @return void
     */
    protected function resetUserPassword(UserTransfer $originalUserTransfer, UserTransfer $updatedUserTransfer): void
    {
        if (
            $updatedUserTransfer->getStatus() === static::USER_STATUS_ACTIVE
            && $originalUserTransfer->getStatus() !== $updatedUserTransfer->getStatus()
        ) {
            $this->authFacade->requestPasswordReset($updatedUserTransfer->getUsername());
        }
    }
}
