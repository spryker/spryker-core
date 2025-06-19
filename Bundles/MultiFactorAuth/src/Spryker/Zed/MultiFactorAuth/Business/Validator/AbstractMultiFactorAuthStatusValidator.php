<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Validator;

use DateTime;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;

abstract class AbstractMultiFactorAuthStatusValidator implements MultiFactorAuthStatusValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     * @param array<int> $additionalStatuses
     * @param \DateTime|null $currentDateTime
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validate(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer,
        array $additionalStatuses = [],
        ?DateTime $currentDateTime = null
    ): MultiFactorAuthValidationResponseTransfer {
        $entityTransfer = $this->extractEntity($multiFactorAuthValidationRequestTransfer);
        $multiFactorAuthTypesCollectionTransfer = $this->getMultiFactorAuthTypesCollectionTransfer(
            $entityTransfer,
            array_unique(array_merge(
                $multiFactorAuthValidationRequestTransfer->getAdditionalStatuses(),
                $additionalStatuses,
            )),
        );

        if ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()->count() === 0) {
            return $this->createMultiFactorAuthValidationResponseTransfer();
        }

        $multiFactorAuthCodeTransfer = $this->getCode(
            $this->buildMultiFactorAuthTransfer($entityTransfer),
        );
        $currentDateTime = $currentDateTime ?? new DateTime();

        if (
            $multiFactorAuthCodeTransfer->getCode() === null ||
            $multiFactorAuthCodeTransfer->getStatus() !== MultiFactorAuthConstants::CODE_VERIFIED ||
            new DateTime($multiFactorAuthCodeTransfer->getExpirationDateOrFail()) < $currentDateTime
        ) {
            return $this->createMultiFactorAuthValidationResponseTransfer(true, $multiFactorAuthCodeTransfer->getStatus());
        }

        if ($multiFactorAuthValidationRequestTransfer->getType() !== null && $multiFactorAuthValidationRequestTransfer->getType() !== $multiFactorAuthCodeTransfer->getType()) {
            return $this->createMultiFactorAuthValidationResponseTransfer(true, MultiFactorAuthConstants::CODE_BLOCKED);
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

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    abstract protected function extractEntity(MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer): AbstractTransfer;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $entityTransfer
     * @param array<int> $additionalStatuses
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    abstract protected function getMultiFactorAuthTypesCollectionTransfer(
        AbstractTransfer $entityTransfer,
        array $additionalStatuses = []
    ): MultiFactorAuthTypesCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    abstract protected function getCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthCodeTransfer;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $entityTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    abstract protected function buildMultiFactorAuthTransfer(AbstractTransfer $entityTransfer): MultiFactorAuthTransfer;
}
