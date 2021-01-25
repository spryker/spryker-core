<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Creator;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Business\AclGroup\AclGroupAdderInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface;
use Spryker\Zed\MerchantUser\MerchantUserConfig;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserCreator implements MerchantUserCreatorInterface
{
    protected const USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE = 'A user with the same email is already connected to another merchant.';

    protected const USER_CREATION_DEFAULT_PASSWORD_LENGTH = 64;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\AclGroup\AclGroupAdderInterface
     */
    protected $aclGroupAdder;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface
     */
    protected $merchantUserEntityManager;

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var \Spryker\Zed\MerchantUser\MerchantUserConfig
     */
    protected $merchantUserConfig;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Business\AclGroup\AclGroupAdderInterface $aclGroupAdder
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface $merchantUserEntityManager
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\MerchantUserConfig $merchantUserConfig
     */
    public function __construct(
        MerchantUserToUtilTextServiceInterface $utilTextService,
        MerchantUserToUserFacadeInterface $userFacade,
        AclGroupAdderInterface $aclGroupAdder,
        MerchantUserEntityManagerInterface $merchantUserEntityManager,
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserConfig $merchantUserConfig
    ) {
        $this->utilTextService = $utilTextService;
        $this->userFacade = $userFacade;
        $this->aclGroupAdder = $aclGroupAdder;
        $this->merchantUserEntityManager = $merchantUserEntityManager;
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantUserConfig = $merchantUserConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function create(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer
    {
        $merchantUserTransfer->requireIdMerchant();
        $merchantUserTransfer->requireUser();

        $merchantUserResponseTransfer = new MerchantUserResponseTransfer();

        $userTransfer = $this->persistUserByMerchantUser($merchantUserTransfer->getUserOrFail());
        $merchantUserTransfer->setIdUser($userTransfer->getIdUser())->setUser($userTransfer);
        $multipleMerchantCheckResponse = $this->checkForMultipleMerchant(
            $merchantUserTransfer,
            $merchantUserResponseTransfer
        );

        if (!$multipleMerchantCheckResponse->getIsSuccessful()) {
            return $multipleMerchantCheckResponse;
        }

        $merchantUserTransfer = $this->merchantUserEntityManager->create($merchantUserTransfer);

        $this->aclGroupAdder->addMerchantAdminToGroup(
            $merchantUserTransfer,
            $this->merchantUserConfig->getMerchantAdminGroupReference()
        );

        return $merchantUserResponseTransfer->setIsSuccessful(true)->setMerchantUser($merchantUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Generated\Shared\Transfer\MerchantUserResponseTransfer $merchantUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function checkForMultipleMerchant(
        MerchantUserTransfer $merchantUserTransfer,
        MerchantUserResponseTransfer $merchantUserResponseTransfer
    ): MerchantUserResponseTransfer {
        if (
            !$this->merchantUserConfig->canUserHaveManyMerchants()
            && $this->hasUserAnotherMerchant($merchantUserTransfer)
        ) {
            $merchantUserResponseTransfer->setIsSuccessful(false)->setMerchantUser($merchantUserTransfer);

            return $merchantUserResponseTransfer->addError((new MessageTransfer())
                ->setMessage(static::USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE));
        }

        return $merchantUserResponseTransfer->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return bool
     */
    protected function hasUserAnotherMerchant(MerchantUserTransfer $merchantUserTransfer): bool
    {
        $existingMerchantUserTransfer = $this->merchantUserRepository->findOne(
            (new MerchantUserCriteriaTransfer())->setIdUser($merchantUserTransfer->getIdUser())
        );

        if (!$existingMerchantUserTransfer) {
            return false;
        }

        return $merchantUserTransfer->getIdMerchant() !== $existingMerchantUserTransfer->getIdMerchant();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function persistUserByMerchantUser(UserTransfer $userTransfer): UserTransfer
    {
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setEmail($userTransfer->getUsername());
        $existingUserTransfer = $this->userFacade->findUser($userCriteriaTransfer);

        if (!$existingUserTransfer) {
            $userTransfer->setPassword(
                $this->utilTextService->generateRandomByteString(static::USER_CREATION_DEFAULT_PASSWORD_LENGTH)
            )->setStatus($this->merchantUserConfig->getUserCreationStatus());

            return $this->userFacade->createUser($userTransfer);
        }

        $existingUserTransfer->fromArray($userTransfer->modifiedToArray(), true);

        return $this->userFacade->updateUser($existingUserTransfer);
    }
}
