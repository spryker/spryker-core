<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\PriceProduct\Business\ConstraintProvider\PriceProductConstraintProviderInterface;
use Spryker\Zed\PriceProduct\Dependency\External\PriceProductToValidationAdapterInterface;

class PriceProductValidator implements PriceProductValidatorInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\ConstraintProvider\PriceProductConstraintProviderInterface
     */
    protected $priceProductConstraintProvider;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\ConstraintProvider\PriceProductConstraintProviderInterface $priceProductConstraintProvider
     * @param \Spryker\Zed\PriceProduct\Dependency\External\PriceProductToValidationAdapterInterface $validationAdapter
     */
    public function __construct(
        PriceProductConstraintProviderInterface $priceProductConstraintProvider,
        PriceProductToValidationAdapterInterface $validationAdapter
    ) {
        $this->priceProductConstraintProvider = $priceProductConstraintProvider;
        $this->validator = $validationAdapter->createValidator();
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validatePrices(ArrayObject $priceProductTransfers): ValidationResponseTransfer
    {
        $validationResponseTransfer = (new ValidationResponseTransfer())->setIsSuccess(true);

        $constraintViolationList = $this->validator->validate(
            $priceProductTransfers,
            $this->priceProductConstraintProvider->getConstraints()
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
