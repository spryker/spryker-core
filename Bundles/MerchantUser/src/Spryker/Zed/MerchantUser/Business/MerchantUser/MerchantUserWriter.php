<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface;

class MerchantUserWriter implements MerchantUserWriterInterface
{
    protected const PASSWORD_LENGTH = 8;
    protected const USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE = 'A user with email %s is already connected with another merchant.';
    protected const MERCHANT_USER_NOT_FOUND_ERROR_MESSAGE = 'Merchant user relation was not found.';

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface
     */
    protected $merchantUserEntityManager;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserReaderInterface
     */
    protected $merchantUserReader;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserReaderInterface $merchantUserReader
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface $merchantUserEntityManager
     */
    public function __construct(
        MerchantUserToUserFacadeInterface $userFacade,
        MerchantUserReaderInterface $merchantUserReader,
        MerchantUserEntityManagerInterface $merchantUserEntityManager
    ) {
        $this->userFacade = $userFacade;
        $this->merchantUserReader = $merchantUserReader;
        $this->merchantUserEntityManager = $merchantUserEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function createMerchantUserByMerchant(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $merchantTransfer->requireEmail()->requireMerchantProfile();

        $userTransfer = $this->resolveUserTransferByMerchantTransfer($merchantTransfer);
        if ($this->checkIsUserHaveAnotherMerchant($userTransfer, $merchantTransfer)) {
            return (new MerchantUserResponseTransfer())
                ->setIsSuccess(false)
                ->addError(
                    (new MessageTransfer())
                        ->setMessage(sprintf(static::USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE, $merchantTransfer->getEmail()))
                );
        }

        $this->updateUserFromMerchantData($userTransfer, $merchantTransfer);
        $merchantUserTransfer = $this->merchantUserEntityManager->createMerchantUser(
            (new MerchantUserTransfer())
                ->setIdMerchant($merchantTransfer->getIdMerchant())
                ->setIdUser($userTransfer->getIdUser())
        );

        return (new MerchantUserResponseTransfer())
            ->setIsSuccess(true)
            ->setMerchantUser($merchantUserTransfer->setUser($userTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function updateUserFromMerchantData(UserTransfer $userTransfer, MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $merchantUserTransferByUser = $this->merchantUserReader->getMerchantUser(
            (new MerchantUserCriteriaFilterTransfer())->setIdUser($userTransfer->getIdUser())->setIdMerchant($merchantTransfer->getIdMerchant())
        );

        if (!$merchantUserTransferByUser) {
            return (new MerchantUserResponseTransfer())
                ->setIsSuccess(false)
                ->addError((new MessageTransfer())->setMessage(static::MERCHANT_USER_NOT_FOUND_ERROR_MESSAGE));
        }

        $userTransfer = $this->userFacade->updateUser($this->fillUserTransferFromMerchantTransfer(
            $this->userFacade->getUserById($userTransfer->getIdUser()),
            $merchantTransfer
        ));

        return (new MerchantUserResponseTransfer())
            ->setIsSuccess(true)
            ->setMerchantUser($merchantUserTransferByUser->setUser($userTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return bool
     */
    protected function checkIsUserHaveAnotherMerchant(UserTransfer $userTransfer, MerchantTransfer $merchantTransfer): bool
    {
        $merchantUserTransfer = $this->merchantUserReader->getMerchantUser(
            (new MerchantUserCriteriaFilterTransfer())->setIdUser($userTransfer->getIdUser())
        );

        if (!$merchantUserTransfer) {
            return false;
        }

        return $merchantUserTransfer->getIdMerchant() !== $merchantTransfer->getIdMerchant();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function resolveUserTransferByMerchantTransfer(MerchantTransfer $merchantTransfer): UserTransfer
    {
        if (!$this->userFacade->hasUserByUsername($merchantTransfer->getEmail())) {
            return $this->createUserByMerchant($merchantTransfer);
        }

        $userTransfer = $this->userFacade->getUserByUsername($merchantTransfer->getEmail());

        return $userTransfer;
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
}
