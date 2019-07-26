<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\Validator;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\DataObjectValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PropertyValidationResultTransfer;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotTemplateConstraintsProviderInterface;
use Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CmsSlotTemplateValidator implements CmsSlotTemplateValidatorInterface
{
    /**
     * @var \Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface
     */
    protected $validationAdapter;

    /**
     * @var \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotTemplateConstraintsProviderInterface
     */
    protected $constraintsProvider;

    /**
     * @param \Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface $validationAdapter
     * @param \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\CmsSlotTemplateConstraintsProviderInterface $constraintsProvider
     */
    public function __construct(
        CmsSlotToValidationAdapterInterface $validationAdapter,
        CmsSlotTemplateConstraintsProviderInterface $constraintsProvider
    ) {
        $this->validationAdapter = $validationAdapter;
        $this->constraintsProvider = $constraintsProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotTemplateTransfer $cmsSlotTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\DataObjectValidationResponseTransfer
     */
    public function validateCmsSlotTemplate(CmsSlotTemplateTransfer $cmsSlotTemplateTransfer): DataObjectValidationResponseTransfer
    {
        $isSuccess = true;
        $validator = $this->validationAdapter->createValidator();
        $properties = $cmsSlotTemplateTransfer->toArray(true, true);
        $dataObjectValidationResponseTransfer = new DataObjectValidationResponseTransfer();

        foreach ($this->constraintsProvider->getConstraintsMap() as $propertyName => $constraintCollection) {
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
