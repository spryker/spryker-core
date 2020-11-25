<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer;
use Generated\Shared\Transfer\PriceProductOfferValidationErrorTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapterInterface;

class PriceProductOfferValidator implements PriceProductOfferValidatorInterface
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
     * @param \Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapterInterface $validationAdapter
     */
    public function __construct(array $constraints, PriceProductOfferToValidationAdapterInterface $validationAdapter)
    {
        $this->constraints = $constraints;
        $this->validator = $validationAdapter->createValidator();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer
     */
    public function validateProductOfferPrices(ArrayObject $priceProductTransfers): PriceProductOfferCollectionValidationResponseTransfer
    {
        $validationResponseTransfer = new PriceProductOfferCollectionValidationResponseTransfer();
        $validationResponseTransfer->setIsSuccessful(true);
        foreach ($priceProductTransfers as $priceProductTransfer) {
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
     * @return \Generated\Shared\Transfer\PriceProductOfferValidationErrorTransfer|null
     */
    protected function validatePrice(PriceProductTransfer $priceProductTransfer): ?PriceProductOfferValidationErrorTransfer
    {
        $constraintViolationList = $this->validator->validate($priceProductTransfer, $this->constraints);
        if (!$constraintViolationList->count()) {
            return null;
        }

        $priceProductOfferValidationErrorTransfer = new PriceProductOfferValidationErrorTransfer();
        $priceProductOfferValidationErrorTransfer->setPriceProduct($priceProductTransfer);

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $validationErrorTransfer = new ValidationErrorTransfer();
            $validationErrorTransfer->setMessage($constraintViolation->getMessage());
            $validationErrorTransfer->setPropertyPath($constraintViolation->getPropertyPath());
            $validationErrorTransfer->setInvalidValue($constraintViolation->getInvalidValue());
            $priceProductOfferValidationErrorTransfer->addValidationError($validationErrorTransfer);
        }

        return $priceProductOfferValidationErrorTransfer;
    }
}
