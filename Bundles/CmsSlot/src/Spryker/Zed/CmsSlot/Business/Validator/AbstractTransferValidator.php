<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\Validator;

use Generated\Shared\Transfer\DataObjectValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PropertyValidationResultTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface;
use Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractTransferValidator
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param \Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface $validationAdapter
     * @param \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface $constraintsProvider
     *
     * @return \Generated\Shared\Transfer\DataObjectValidationResponseTransfer
     */
    protected function validate(
        AbstractTransfer $transfer,
        CmsSlotToValidationAdapterInterface $validationAdapter,
        ConstraintsProviderInterface $constraintsProvider
    ): DataObjectValidationResponseTransfer {
        $isSuccess = true;
        $validator = $validationAdapter->createValidator();
        $properties = $transfer->toArray(true, true);
        $dataObjectValidationResponseTransfer = new DataObjectValidationResponseTransfer();

        foreach ($constraintsProvider->getConstraintsMap() as $propertyName => $constraintCollection) {
            $violations = $validator->validate(
                $properties[$propertyName],
                $constraintCollection
            );

            if ($violations->count()) {
                $propertyValidationResultTransfer = $this->getPropertyValidationResultTransfer($propertyName, $violations);
                $dataObjectValidationResponseTransfer->addValidationResults($propertyValidationResultTransfer);
                $isSuccess = false;
            }
        }

        return $dataObjectValidationResponseTransfer->setIsSuccess($isSuccess);
    }

    /**
     * @param string $propertyName
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $violations
     *
     * @return \Generated\Shared\Transfer\PropertyValidationResultTransfer
     */
    protected function getPropertyValidationResultTransfer(
        string $propertyName,
        ConstraintViolationListInterface $violations
    ): PropertyValidationResultTransfer {
        $propertyValidationResultTransfer = new PropertyValidationResultTransfer();
        $propertyValidationResultTransfer->setPropertyName($propertyName);

        /** @var \Symfony\Component\Validator\ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $propertyValidationResultTransfer->addMessage(
                (new MessageTransfer())->setValue($violation->getMessage())
            );
        }

        return $propertyValidationResultTransfer;
    }
}
