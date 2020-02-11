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
use Spryker\Zed\MerchantUser\Business\AclGroup\AclGroupAdderInterface;
use Spryker\Zed\MerchantUser\Business\User\UserMapperInterface;
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
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\User\UserMapperInterface
     */
    protected $userMapper;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\AclGroup\AclGroupAdderInterface
     */
    protected $aclGroupAdder;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MerchantUser\Business\User\UserMapperInterface $userMapper
     * @param \Spryker\Zed\MerchantUser\Business\AclGroup\AclGroupAdderInterface $aclGroupAdder
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface $merchantUserEntityManager
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\MerchantUserConfig $merchantUserConfig
     */
    public function __construct(
        MerchantUserToUtilTextServiceInterface $utilTextService,
        MerchantUserToUserFacadeInterface $userFacade,
        UserMapperInterface $userMapper,
        AclGroupAdderInterface $aclGroupAdder,
        MerchantUserEntityManagerInterface $merchantUserEntityManager,
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserConfig $merchantUserConfig
    ) {
        $this->utilTextService = $utilTextService;
        $this->merchantUserEntityManager = $merchantUserEntityManager;
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantUserConfig = $merchantUserConfig;
        $this->userFacade = $userFacade;
        $this->userMapper = $userMapper;
        $this->aclGroupAdder = $aclGroupAdder;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function createMerchantAdmin(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $merchantUserTransfer = (new MerchantUserTransfer())
            ->setMerchant($merchantTransfer)
            ->setUser($this->resolveUserTransferByMerchant($merchantTransfer));

        $merchantUserTransfer = $this->aclGroupAdder->addMerchantAdminToGroupByReference(
            $merchantUserTransfer,
            $this->merchantUserConfig->getMerchantAdminGroupReference()
        );

        return $this->create($merchantUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function create(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer
    {
        $merchantUserTransfer->requireMerchant()->getMerchant()->requireEmail();
        $merchantUserTransfer->requireUser()->getUser()->requireIdUser();

        if (
            !$this->merchantUserConfig->canUserHaveManyMerchants()
            && $this->hasUserAnotherMerchant($merchantUserTransfer->getUser(), $merchantUserTransfer->getMerchant())
        ) {
            $merchantUserResponseTransfer = $this->createMerchantUserResponseTransfer($merchantUserTransfer);

            return $this->addMessageToMerchantUserResponseTransfer(
                $merchantUserResponseTransfer,
                sprintf(static::USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE, $merchantUserTransfer->getMerchant()->getEmail())
            );
        }

        $merchantUserTransfer = $this->merchantUserEntityManager->create(
            (new MerchantUserTransfer())
                ->setIdMerchant($merchantUserTransfer->getMerchant()->getIdMerchant())
                ->setIdUser($merchantUserTransfer->getUser()->getIdUser())
        );

        return $this->createMerchantUserResponseTransfer($merchantUserTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserResponseTransfer $merchantUserResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function addMessageToMerchantUserResponseTransfer(
        MerchantUserResponseTransfer $merchantUserResponseTransfer,
        string $message
    ): MerchantUserResponseTransfer {
        $merchantUserResponseTransfer->addError((new MessageTransfer())->setMessage($message));

        return $merchantUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    protected function createMerchantUserResponseTransfer(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer
    {
        return (new MerchantUserResponseTransfer())
            ->setIsSuccessful(false)
            ->setMerchantUser($merchantUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return bool
     */
    protected function hasUserAnotherMerchant(UserTransfer $userTransfer, MerchantTransfer $merchantTransfer): bool
    {
        $merchantUserTransfer = $this->merchantUserRepository->findOne(
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
    protected function resolveUserTransferByMerchant(MerchantTransfer $merchantTransfer): UserTransfer
    {
        if (!$this->userFacade->hasUserByUsername($merchantTransfer->getEmail())) {
            $userTransfer = $this->userMapper->mapMerchantTransferToUserTransfer($merchantTransfer, new UserTransfer())
                ->setPassword($this->utilTextService->generateRandomByteString(static::USER_CREATION_DEFAULT_PASSWORD_LENGTH))
                ->setStatus($this->merchantUserConfig->getUserCreationStatus());

            return $this->userFacade->createUser($userTransfer);
        }
        $userTransfer = $this->userFacade->getUserByUsername($merchantTransfer->getEmail());

        return $this->userFacade->updateUser($this->userMapper->mapMerchantTransferToUserTransfer($merchantTransfer, $userTransfer));
    }
}
