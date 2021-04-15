<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Reader;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\MerchantUser\Business\Exception\CurrentMerchantUserNotFoundException;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToMerchantFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class CurrentMerchantUserReader implements CurrentMerchantUserReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        MerchantUserToUserFacadeInterface $userFacade,
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserToMerchantFacadeInterface $merchantFacade
    ) {
        $this->userFacade = $userFacade;
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @throws \Spryker\Zed\MerchantUser\Business\Exception\CurrentMerchantUserNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function getCurrentMerchantUser(): MerchantUserTransfer
    {
        $userTransfer = $this->userFacade->getCurrentUser();
        $merchantUserCriteriaFilterTransfer = (new MerchantUserCriteriaTransfer())->setIdUser(
            $userTransfer->getIdUser()
        );

        $merchantUserTransfers = $this->merchantUserRepository->getMerchantUsers($merchantUserCriteriaFilterTransfer);

        if (count($merchantUserTransfers) === 0) {
            throw new CurrentMerchantUserNotFoundException(
                'Current merchant user was not found'
            );
        }

        $merchantUserTransfer = reset($merchantUserTransfers);
        $merchantUserTransfer->setUser($userTransfer);

        return $this->expandWithMerchant($merchantUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function expandWithMerchant(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
    {
        $merchantTransfer = $this->merchantFacade->findOne(
            (new MerchantCriteriaTransfer())->setIdMerchant($merchantUserTransfer->getIdMerchant())
        );

        return $merchantUserTransfer->setMerchant($merchantTransfer);
    }
}
