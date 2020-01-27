<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Spryker\Zed\MerchantUser\Business\User\UserWriterInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserUpdater implements MerchantUserUpdaterInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserCreatorInterface
     */
    protected $merchantUserCreator;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\User\UserWriterInterface
     */
    protected $userWriter;

    /**
     * @param \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserCreatorInterface $merchantUserCreator
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\Business\User\UserWriterInterface $userWriter
     */
    public function __construct(
        MerchantUserCreatorInterface $merchantUserCreator,
        MerchantUserRepositoryInterface $merchantUserRepository,
        UserWriterInterface $userWriter
    ) {
        $this->merchantUserRepository = $merchantUserRepository;
        $this->merchantUserCreator = $merchantUserCreator;
        $this->userWriter = $userWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $originalMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostUpdate(MerchantTransfer $originalMerchantTransfer, MerchantTransfer $updatedMerchantTransfer): MerchantResponseTransfer
    {
        $merchantUserTransfer = $this->merchantUserRepository->findOne(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($updatedMerchantTransfer->getIdMerchant())
        );
        if (!$merchantUserTransfer) {
            return $this->merchantUserCreator->handleMerchantPostCreate($updatedMerchantTransfer);
        }

        $this->userWriter->syncUserWithMerchant($updatedMerchantTransfer, $merchantUserTransfer);

        return (new MerchantResponseTransfer())->setIsSuccess(true)->setMerchant($updatedMerchantTransfer);
    }
}
