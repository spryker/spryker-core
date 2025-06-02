<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Validator\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\MultiFactorAuth\Business\Validator\AbstractMultiFactorAuthStatusValidator;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface;

class CustomerMultiFactorAuthStatusValidator extends AbstractMultiFactorAuthStatusValidator
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
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function extractEntity(MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer): CustomerTransfer
    {
        return $multiFactorAuthValidationRequestTransfer->getCustomerOrFail();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $customerTransfer
     * @param array<int> $additionalStatuses
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    protected function getMultiFactorAuthTypesCollectionTransfer(
        AbstractTransfer $customerTransfer,
        array $additionalStatuses = []
    ): MultiFactorAuthTypesCollectionTransfer {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        return $this->repository->getCustomerMultiFactorAuthTypes($customerTransfer, $additionalStatuses);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    protected function getCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthCodeTransfer
    {
        return $this->repository->getCustomerCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    protected function buildMultiFactorAuthTransfer(AbstractTransfer $customerTransfer): MultiFactorAuthTransfer
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        return (new MultiFactorAuthTransfer())
            ->setCustomer($customerTransfer)
            ->setType($this->repository->getVerifiedCustomerMultiFactorAuthType($customerTransfer));
    }
}
