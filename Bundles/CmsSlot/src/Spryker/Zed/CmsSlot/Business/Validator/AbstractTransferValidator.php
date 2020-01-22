<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\Validator;

use Generated\Shared\Transfer\ConstraintViolationTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface;
use Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToSymfonyValidatorAdapterInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractTransferValidator
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param \Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToSymfonyValidatorAdapterInterface $validatorAdapter
     * @param \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface $constraintsProvider
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    protected function validate(
        AbstractTransfer $transfer,
        CmsSlotToSymfonyValidatorAdapterInterface $validatorAdapter,
        ConstraintsProviderInterface $constraintsProvider
    ): ValidationResponseTransfer {
        $isSuccess = true;
        $validator = $validatorAdapter->createValidator();
        $properties = $transfer->toArray(true, true);
        $validationResponseTransfer = new ValidationResponseTransfer();

        foreach ($constraintsProvider->getConstraintsMap() as $propertyName => $constraintCollection) {
            $violations = $validator->validate(
                $properties[$propertyName],
                $constraintCollection
            );

            if ($violations->count()) {
                $constraintViolationTransfer = $this->getConstraintViolationTransfer($propertyName, $violations);
                $validationResponseTransfer->addConstraintViolations($constraintViolationTransfer);
                $isSuccess = false;
            }
        }

        return $validationResponseTransfer->setIsSuccess($isSuccess);
    }

    /**
     * @param string $propertyName
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $violations
     *
     * @return \Generated\Shared\Transfer\ConstraintViolationTransfer
     */
    protected function getConstraintViolationTransfer(
        string $propertyName,
        ConstraintViolationListInterface $violations
    ): ConstraintViolationTransfer {
        $constraintViolationTransfer = new ConstraintViolationTransfer();
        $constraintViolationTransfer->setPropertyName($propertyName);

        /** @var \Symfony\Component\Validator\ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $constraintViolationTransfer->addMessage(
                (new MessageTransfer())->setValue($violation->getMessage())
            );
        }

        return $constraintViolationTransfer;
    }
}
