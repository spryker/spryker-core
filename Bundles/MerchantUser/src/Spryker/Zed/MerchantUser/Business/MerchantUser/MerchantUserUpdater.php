<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use InvalidArgumentException;
use Spryker\Zed\MerchantUser\Business\User\UserMapperInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserUpdater implements MerchantUserUpdaterInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\User\UserMapperInterface
     */
    protected $userMapper;

    /**
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Business\User\UserMapperInterface $userMapper
     */
    public function __construct(
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserToUserFacadeInterface $userFacade,
        UserMapperInterface $userMapper
    ) {
        $this->merchantUserRepository = $merchantUserRepository;
        $this->userFacade = $userFacade;
        $this->userMapper = $userMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function updateMerchantAdmin(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $merchantTransfer->requireMerchantProfile();

        $merchantUserTransfer = $this->merchantUserRepository->findOne(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($merchantTransfer->getIdMerchant())
        );
        if (!$merchantUserTransfer) {
            throw new InvalidArgumentException(sprintf('Could not find Merchant Admin by Merchant id %d', $merchantTransfer->getIdMerchant()));
        }

        $userTransfer = $this->userFacade->getUserById($merchantUserTransfer->getIdUser());
        $userTransfer = $this->userMapper->mapMerchantTransferToUserTransfer($merchantTransfer, $userTransfer);

        $this->userFacade->updateUser($userTransfer);

        $merchantUserTransfer->setMerchant($merchantTransfer);

        return (new MerchantUserResponseTransfer())->setIsSuccessful(true)->setMerchantUser($merchantUserTransfer);
    }
}
