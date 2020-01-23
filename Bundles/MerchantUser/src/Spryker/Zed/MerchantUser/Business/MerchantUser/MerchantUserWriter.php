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
use Spryker\Zed\MerchantUser\MerchantUserConfig;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserWriter implements MerchantUserWriterInterface
{
    protected const USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE = 'A user with the same email is already connected to another merchant.';

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
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface $merchantUserEntityManager
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\MerchantUserConfig $merchantUserConfig
     */
    public function __construct(
        MerchantUserEntityManagerInterface $merchantUserEntityManager,
        MerchantUserRepositoryInterface $merchantUserRepository,
        MerchantUserConfig $merchantUserConfig
    ) {
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantUserEntityManager = $merchantUserEntityManager;
        $this->merchantUserConfig = $merchantUserConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function create(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer
    {
        $merchantUserTransfer->requireMerchant()->getMerchant()->requireEmail();
        $merchantUserTransfer->requireUser()->getUser()->requireIdUser();

        if (!$this->merchantUserConfig->canUserHaveManyMerchants()
            && $this->hasUserAnotherMerchant($merchantUserTransfer->getUser(), $merchantUserTransfer->getMerchant())
        ) {
            return (new MerchantUserResponseTransfer())
                ->setIsSuccess(false)
                ->addError(
                    (new MessageTransfer())
                        ->setMessage(sprintf(static::USER_HAVE_ANOTHER_MERCHANT_ERROR_MESSAGE, $merchantUserTransfer->getMerchant()->getEmail()))
                );
        }

        $merchantUserTransfer = $this->merchantUserEntityManager->createMerchantUser(
            (new MerchantUserTransfer())
                ->setIdMerchant($merchantUserTransfer->getMerchant()->getIdMerchant())
                ->setIdUser($merchantUserTransfer->getUser()->getIdUser())
        );

        return (new MerchantUserResponseTransfer())
            ->setIsSuccess(true)
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
}
