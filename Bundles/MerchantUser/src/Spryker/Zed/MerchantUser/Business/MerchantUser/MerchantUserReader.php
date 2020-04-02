<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\MerchantUser\Business\Exception\MerchantUserNotFoundException;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserReader implements MerchantUserReaderInterface
{
    protected const EXCEPTION_MESSAGE_MERCHANT_USER_NOT_FOUND_FOR_USER = 'Merchant user was not found by provided user id %s';

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     */
    public function __construct(
        MerchantUserToUserFacadeInterface $userFacade,
        MerchantUserRepositoryInterface $merchantUserRepository
    ) {
        $this->userFacade = $userFacade;
        $this->merchantUserRepository = $merchantUserRepository;
    }

    /**
     * @throws \Spryker\Zed\MerchantUser\Business\Exception\MerchantUserNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function getCurrentMerchantUser(): MerchantUserTransfer
    {
        $userTransfer = $this->userFacade->getCurrentUser();
        $merchantUserCriteriaFilterTransfer = (new MerchantUserCriteriaFilterTransfer())->setIdUser(
            $userTransfer->getIdUser()
        );

        $merchantUserTransfers = $this->merchantUserRepository->getMerchantUsers($merchantUserCriteriaFilterTransfer);

        if (count($merchantUserTransfers) === 0) {
            throw new MerchantUserNotFoundException(sprintf(
                static::EXCEPTION_MESSAGE_MERCHANT_USER_NOT_FOUND_FOR_USER,
                $userTransfer->getIdUser()
            ));
        }

        return $merchantUserTransfers[0];
    }
}
