<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Validator\Customer;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\MultiFactorAuth\Business\Validator\MultiFactorAuthStatusValidatorInterface;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface;

class CustomerMultiFactorAuthStatusValidator implements MultiFactorAuthStatusValidatorInterface
{
    /**
     * @param \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface $repository
     */
    public function __construct(
        protected MultiFactorAuthRepositoryInterface $repository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     * @param string|null $currentDateTime
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validate(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer,
        ?string $currentDateTime = null
    ): MultiFactorAuthValidationResponseTransfer {
        $customerTransfer = $multiFactorAuthValidationRequestTransfer->getCustomerOrFail();
        $customerMultiFactorAuthTypesCollectionTransfer = $this->repository->getCustomerMultiFactorAuthTypes($customerTransfer);

        if ($customerMultiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()->count() === 0) {
            return $this->createMultiFactorAuthValidationResponseTransfer();
        }

        $verifiedType = $this->repository->getVerifiedCustomerMultiFactorAuthType($customerTransfer);
        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setCustomer($customerTransfer)
            ->setType($verifiedType);

        $customerMultiFactorAuthCodeTransfer = $this->repository->getCustomerCode($multiFactorAuthTransfer);
        $currentDateTime = $currentDateTime ?? date('Y-m-d H:i:s');

        if (
            $customerMultiFactorAuthCodeTransfer->getCode() === null ||
            $customerMultiFactorAuthCodeTransfer->getStatus() !== MultiFactorAuthConstants::CODE_VERIFIED ||
            $customerMultiFactorAuthCodeTransfer->getExpirationDate() < $currentDateTime
        ) {
            return $this->createMultiFactorAuthValidationResponseTransfer(true, $customerMultiFactorAuthCodeTransfer->getStatus());
        }

        return $this->createMultiFactorAuthValidationResponseTransfer();
    }

    /**
     * @param bool $isRequired
     * @param int|null $status
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    protected function createMultiFactorAuthValidationResponseTransfer(
        bool $isRequired = false,
        ?int $status = MultiFactorAuthConstants::CODE_VERIFIED
    ): MultiFactorAuthValidationResponseTransfer {
        return (new MultiFactorAuthValidationResponseTransfer())
            ->setStatus($status)
            ->setIsRequired($isRequired);
    }
}
