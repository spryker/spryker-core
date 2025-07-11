<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Validator\User;

use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\MultiFactorAuth\Business\Validator\AbstractMultiFactorAuthStatusValidator;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface;

class UserMultiFactorAuthStatusValidator extends AbstractMultiFactorAuthStatusValidator
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
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function extractEntity(MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer): UserTransfer
    {
        return $multiFactorAuthValidationRequestTransfer->getUserOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array<int> $statuses
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    protected function getMultiFactorAuthTypesCollectionTransfer(
        AbstractTransfer $userTransfer,
        array $statuses = []
    ): MultiFactorAuthTypesCollectionTransfer {
        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())
            ->setUser($userTransfer)
            ->setStatuses($statuses);

        return $this->repository->getUserMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    protected function getCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthCodeTransfer
    {
        return $this->repository->getUserCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    protected function buildMultiFactorAuthTransfer(AbstractTransfer $userTransfer): MultiFactorAuthTransfer
    {
        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        return (new MultiFactorAuthTransfer())
            ->setUser($userTransfer)
            ->setType($this->repository->getVerifiedUserMultiFactorAuthType($userTransfer));
    }
}
