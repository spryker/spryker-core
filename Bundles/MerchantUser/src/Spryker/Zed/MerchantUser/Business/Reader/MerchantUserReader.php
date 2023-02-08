<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Reader;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserReader implements MerchantUserReaderInterface
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
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     */
    public function __construct(
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserToUserFacadeInterface $userFacade
    ) {
        $this->merchantUserRepository = $merchantUserRepository;
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findOne(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): ?MerchantUserTransfer
    {
        $merchantUserTransfer = $this->merchantUserRepository->findOne($merchantUserCriteriaTransfer);

        if (!$merchantUserTransfer || !$merchantUserCriteriaTransfer->getWithUser()) {
            return $merchantUserTransfer;
        }

        if (!$merchantUserTransfer->getIdUser()) {
            return $merchantUserTransfer;
        }

        return $merchantUserTransfer->setUser(
            $this->findUserTransfer($merchantUserTransfer->getIdUserOrFail()),
        );
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUserTransfer(int $idUser): ?UserTransfer
    {
        $userCriteriaTransfer = $this->createUserCriteriaTransfer($idUser);
        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    protected function createUserCriteriaTransfer(int $idUser): UserCriteriaTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())->addIdUser($idUser);

        return (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
    }
}
