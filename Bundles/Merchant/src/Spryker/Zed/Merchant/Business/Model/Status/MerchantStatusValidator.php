<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model\Status;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Merchant\Business\Exception\MerchantNotFoundException;
use Spryker\Zed\Merchant\Business\Exception\MerchantStatusTransitionNotAllowedException;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantStatusValidator implements MerchantStatusValidatorInterface
{
    protected const ERROR_TRANSITION_TO_STATUS_NOT_ALLOWED = 'Transition to status \'%s\' is not allowed.';

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusReaderInterface
     */
    protected $merchantStatusReader;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $repository
     * @param \Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusReaderInterface $merchantStatusReader
     */
    public function __construct(
        MerchantRepositoryInterface $repository,
        MerchantStatusReaderInterface $merchantStatusReader
    ) {
        $this->repository = $repository;
        $this->merchantStatusReader = $merchantStatusReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @throws \Spryker\Zed\Merchant\Business\Exception\MerchantNotFoundException
     *
     * @return void
     */
    public function validateTransitionToStatus(MerchantTransfer $merchantTransfer): void
    {
        $existingMerchantTransfer = $this->repository->findMerchantById($merchantTransfer->getIdMerchant());

        if ($existingMerchantTransfer === null) {
            throw new MerchantNotFoundException();
        }

        if (!$this->isStatusChanged($merchantTransfer, $existingMerchantTransfer)) {
            return;
        }

        if (!$this->isTransitionToStatusAllowed($merchantTransfer, $existingMerchantTransfer)) {
            $this->throwException($merchantTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $existingMerchantTransfer
     *
     * @return bool
     */
    protected function isStatusChanged(MerchantTransfer $merchantTransfer, MerchantTransfer $existingMerchantTransfer): bool
    {
        return $merchantTransfer->getStatus() !== $existingMerchantTransfer->getStatus();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $existingMerchantTransfer
     *
     * @return bool
     */
    protected function isTransitionToStatusAllowed(MerchantTransfer $merchantTransfer, MerchantTransfer $existingMerchantTransfer): bool
    {
        $nextStatuses = $this->merchantStatusReader->getNextStatuses($existingMerchantTransfer->getStatus());

        return in_array($merchantTransfer->getStatus(), $nextStatuses);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @throws \Spryker\Zed\Merchant\Business\Exception\MerchantStatusTransitionNotAllowedException
     *
     * @return void
     */
    protected function throwException(MerchantTransfer $merchantTransfer): void
    {
        throw new MerchantStatusTransitionNotAllowedException(sprintf(static::ERROR_TRANSITION_TO_STATUS_NOT_ALLOWED, $merchantTransfer->getStatus()));
    }
}
