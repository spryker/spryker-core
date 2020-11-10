<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Authorizer;

use DateTime;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;

class MerchantUserAuthorizer implements MerchantUserAuthorizerInterface
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
     * @return void
     */
    public function authorizeMerchantUser(MerchantUserTransfer $merchantUserTransfer): void
    {
        $userTransfer = $merchantUserTransfer->requireUser()->getUser();
        $this->userFacade->setCurrentUser($userTransfer);
        $this->updateUserLastLoginDate($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    protected function updateUserLastLoginDate(UserTransfer $userTransfer): void
    {
        $userTransfer->setLastLogin((new DateTime())->format(DateTime::ATOM));
        $this->userFacade->updateUser(clone $userTransfer);
    }
}
