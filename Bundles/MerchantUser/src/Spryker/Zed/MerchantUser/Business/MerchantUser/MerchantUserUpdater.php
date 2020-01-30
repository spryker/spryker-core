<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\MerchantUser;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Propel\Runtime\Exception\EntityNotFoundException;
use Spryker\Zed\MerchantUser\Business\User\UserWriterInterface;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class MerchantUserUpdater implements MerchantUserUpdaterInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @var \Spryker\Zed\MerchantUser\Business\User\UserWriterInterface
     */
    protected $userWriter;

    /**
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     * @param \Spryker\Zed\MerchantUser\Business\User\UserWriterInterface $userWriter
     */
    public function __construct(
        MerchantUserRepositoryInterface $merchantUserRepository,
        UserWriterInterface $userWriter
    ) {
        $this->merchantUserRepository = $merchantUserRepository;
        $this->userWriter = $userWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function updateMerchantAdmin(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer
    {
        $merchantUserTransfer = $this->merchantUserRepository->findOne(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($merchantTransfer->getIdMerchant())
        );
        if (!$merchantUserTransfer) {
            throw new EntityNotFoundException(sprintf('Could not find Merchant Admin by Merchant id %d', $merchantTransfer->getIdMerchant()));
        }

        $this->userWriter->updateFromMerchant($merchantTransfer, $merchantUserTransfer);
        $merchantUserTransfer->setMerchant($merchantTransfer);

        return (new MerchantUserResponseTransfer())->setIsSuccessful(true)->setMerchantUser($merchantUserTransfer);
    }
}
