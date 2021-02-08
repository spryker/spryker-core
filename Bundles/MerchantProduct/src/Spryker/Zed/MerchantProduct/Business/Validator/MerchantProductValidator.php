<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Validator;

use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\MerchantProduct\Dependency\External\MerchantProductToValidationAdapterInterface;

class MerchantProductValidator implements MerchantProductValidatorInterface
{
    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @var array|\Symfony\Component\Validator\Constraint[]
     */
    protected $merchantProductConstraints;

    /**
     * @param \Spryker\Zed\MerchantProduct\Dependency\External\MerchantProductToValidationAdapterInterface $validationAdapter
     * @param \Symfony\Component\Validator\Constraint[] $merchantProductConstraints
     */
    public function __construct(
        MerchantProductToValidationAdapterInterface $validationAdapter,
        array $merchantProductConstraints
    ) {
        $this->validator = $validationAdapter->createValidator();
        $this->merchantProductConstraints = $merchantProductConstraints;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateMerchantProduct(MerchantProductTransfer $merchantProductTransfer): ValidationResponseTransfer
    {
        $validationResponseTransfer = (new ValidationResponseTransfer())->setIsSuccess(true);

        $constraintViolationList = $this->validator->validate(
            $merchantProductTransfer,
            $this->merchantProductConstraints
        );

        if (!$constraintViolationList->count()) {
            return $validationResponseTransfer;
        }

        $validationResponseTransfer->setIsSuccess(false);

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $validationErrorTransfer = (new ValidationErrorTransfer())
                ->setMessage($constraintViolation->getMessage())
                ->setPropertyPath($constraintViolation->getPropertyPath())
                ->setInvalidValue($constraintViolation->getInvalidValue())
                ->setRoot($constraintViolation->getRoot());
            $validationResponseTransfer->addValidationError($validationErrorTransfer);
        }

        return $validationResponseTransfer;
    }
}
