<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserErrorTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserWriter implements MerchantUserWriterInterface
{
    protected const PASSWORD_LENGTH = 8;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface
     */
    protected $merchantUserEntityManager;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface $merchantUserEntityManager
     */
    public function __construct(
        MerchantUserToUserFacadeInterface $userFacade,
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserEntityManagerInterface $merchantUserEntityManager
    ) {
        $this->userFacade = $userFacade;
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantUserEntityManager = $merchantUserEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function createMerchantUserByMerchant(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $merchantTransfer->requireEmail()
            ->requireMerchantProfile();

        $merchantUserResponseTransfer = $this->createMerchantUserResponseTransfer();

        $merchantUserTransferByMerchant = $this->findMerchantUser(null, $merchantTransfer);
        if ($merchantUserTransferByMerchant) {
            $userTransfer = $this->userFacade->getUserById($merchantUserTransferByMerchant->getFkUser());
            $this->userFacade->updateUser($this->fillUserTransferFromMerchantTransfer($userTransfer, $merchantTransfer));

            return $merchantUserResponseTransfer->setIsSuccess(true)
                ->setMerchantUser($merchantUserTransferByMerchant);
        }

        if ($this->userFacade->hasUserByUsername($merchantTransfer->getEmail())) {
            $userTransfer = $this->userFacade->getUserByUsername($merchantTransfer->getEmail());
            $this->userFacade->updateUser($this->fillUserTransferFromMerchantTransfer($userTransfer, $merchantTransfer));
        } else {
            $userTransfer = $this->createUserByMerchant($merchantTransfer);
        }

        $merchantUserTransferByUser = $this->findMerchantUser($userTransfer);
        if (!$merchantUserTransferByUser) {
            $merchantUserTransferByUser = $this->merchantUserEntityManager->create(
                (new MerchantUserTransfer())
                    ->setFkMerchant($merchantTransfer->getIdMerchant())
                    ->setFkUser($userTransfer->getIdUser())
            );
        }

        if ($merchantUserTransferByUser->getFkMerchant() !== $merchantTransfer->getIdMerchant()) {
            return $this->addErrorMessage(
                $merchantUserResponseTransfer,
                sprintf('A user with email %s is already connected with another merchant', $merchantTransfer->getEmail())
            );
        }

        return $merchantUserResponseTransfer->setIsSuccess(true)
            ->setMerchantUser($merchantUserTransferByUser);
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function createMerchantUserResponseTransfer(): MerchantUserResponseTransfer
    {
        return (new MerchantUserResponseTransfer())
            ->setIsSuccess(false);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserResponseTransfer $merchantUserResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function addErrorMessage(MerchantUserResponseTransfer $merchantUserResponseTransfer, string $message): MerchantUserResponseTransfer
    {
        return $merchantUserResponseTransfer->addError((new MerchantUserErrorTransfer())->setMessage($message));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createUserByMerchant(MerchantTransfer $merchantTransfer): UserTransfer
    {
        $utilTextService = new UtilTextService();
        $userTransfer = $this->fillUserTransferFromMerchantTransfer(new UserTransfer(), $merchantTransfer)
            ->setPassword($utilTextService->generateRandomString(static::PASSWORD_LENGTH))
            ->setStatus(SpyUserTableMap::COL_STATUS_BLOCKED);

        return $this->userFacade->createUser($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function fillUserTransferFromMerchantTransfer(UserTransfer $userTransfer, MerchantTransfer $merchantTransfer): UserTransfer
    {
        return $userTransfer
            ->setFirstName($merchantTransfer->getMerchantProfile()->getContactPersonFirstName())
            ->setLastName($merchantTransfer->getMerchantProfile()->getContactPersonLastName())
            ->setUsername($merchantTransfer->getEmail());
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer|null $userTransfer |null
     * @param \Generated\Shared\Transfer\MerchantTransfer|null $merchantTransfer |null
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    protected function findMerchantUser(?UserTransfer $userTransfer = null, ?MerchantTransfer $merchantTransfer = null): ?MerchantUserTransfer
    {
        $merchantUserCriteriaFilterTransfer = new MerchantUserCriteriaFilterTransfer();

        if ($userTransfer) {
            $merchantUserCriteriaFilterTransfer->setFkUser($userTransfer->getIdUser());
        }

        if ($merchantTransfer) {
            $merchantUserCriteriaFilterTransfer->setFkMerchant($merchantTransfer->getIdMerchant());
        }

        return $this->merchantUserRepository->findOne($merchantUserCriteriaFilterTransfer);
    }
}
