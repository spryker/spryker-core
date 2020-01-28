<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\User;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;

class UserWriter implements UserWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\User\UserMapperInterface
     */
    protected $userMapper;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Business\User\UserMapperInterface $userMapper
     */
    public function __construct(
        MerchantUserToUserFacadeInterface $userFacade,
        UserMapperInterface $userMapper
    ) {
        $this->userFacade = $userFacade;
        $this->userMapper = $userMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function updateFromMerchant(
        MerchantTransfer $merchantTransfer,
        MerchantUserTransfer $merchantUserTransfer
    ): UserTransfer {
        $merchantTransfer->requireMerchantProfile();

        $userTransfer = $this->userFacade->getUserById($merchantUserTransfer->getIdUser());
        $userTransfer = $this->userMapper->mapMerchantTransferToUserTransfer($merchantTransfer, $userTransfer);

        return $this->userFacade->updateUser($userTransfer);
    }
}
