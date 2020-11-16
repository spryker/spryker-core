<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator;

use Generated\Shared\Transfer\PriceProductCollectionTransfer;
use Generated\Shared\Transfer\PriceProductCollectionValidationResponseTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceProductValidationErrorTransfer;
use Spryker\Zed\PriceProduct\Dependency\External\PriceProductToValidationAdapterInterface;

class PriceProductValidator implements PriceProductValidatorInterface
{
    /**
     * @var \Symfony\Component\Validator\Constraint[]
     */
    protected $constraints;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @param \Symfony\Component\Validator\Constraint[] $constraints
     * @param \Spryker\Zed\PriceProduct\Dependency\External\PriceProductToValidationAdapterInterface $validationAdapter
     */
    public function __construct(array $constraints, PriceProductToValidationAdapterInterface $validationAdapter)
    {
        $this->constraints = $constraints;
        $this->validator = $validationAdapter->createValidator();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCollectionTransfer $priceProductCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCollectionValidationResponseTransfer
     */
    public function validatePrices(PriceProductCollectionTransfer $priceProductCollectionTransfer): PriceProductCollectionValidationResponseTransfer
    {
        $validationResponseTransfer = new PriceProductCollectionValidationResponseTransfer();
        $validationResponseTransfer->setIsSuccessful(true);
        foreach ($priceProductCollectionTransfer->getPriceProducts() as $priceProductTransfer) {
            $validationError = $this->validatePrice($priceProductTransfer);
            if ($validationError) {
                $validationResponseTransfer->setIsSuccessful(false);
                $validationResponseTransfer->addError($validationError);
            }
        }

        return $validationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductValidationErrorTransfer|null
     */
    protected function validatePrice(PriceProductTransfer $priceProductTransfer): ?PriceProductValidationErrorTransfer
    {
        $constraintViolationList = $this->validator->validate($priceProductTransfer, $this->constraints);
        if (!$constraintViolationList->count()) {
            return null;
        }

        $validationError = new PriceProductValidationErrorTransfer();
        $validationError->setPriceProduct($priceProductTransfer);

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $validationError->addMessage($constraintViolation->getMessage());
        }

        return $validationError;
    }
}
