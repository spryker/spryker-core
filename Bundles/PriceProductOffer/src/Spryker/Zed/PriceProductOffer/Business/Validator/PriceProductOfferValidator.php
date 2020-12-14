<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Spryker\Zed\PriceProductOffer\Business\ConstraintProvider\PriceProductOfferConstraintProviderInterface;
use Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapterInterface;

class PriceProductOfferValidator implements PriceProductOfferValidatorInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Business\ConstraintProvider\PriceProductOfferConstraintProviderInterface
     */
    protected $priceProductOfferConstraintProvider;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Business\ConstraintProvider\PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider
     * @param \Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapterInterface $validationAdapter
     */
    public function __construct(
        PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider,
        PriceProductOfferToValidationAdapterInterface $validationAdapter
    ) {
        $this->priceProductOfferConstraintProvider = $priceProductOfferConstraintProvider;
        $this->validator = $validationAdapter->createValidator();
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer
     */
    public function validateProductOfferPrices(ArrayObject $priceProductTransfers): PriceProductOfferCollectionValidationResponseTransfer
    {
        $validationResponseTransfer = new PriceProductOfferCollectionValidationResponseTransfer();
        $validationResponseTransfer->setIsSuccessful(true);

        $constraintViolationList = $this->validator->validate($priceProductTransfers, $this->priceProductOfferConstraintProvider->getConstraints());

        if (!$constraintViolationList->count()) {
            return $validationResponseTransfer;
        }

        $validationResponseTransfer->setIsSuccessful(false);

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $validationErrorTransfer = new ValidationErrorTransfer();
            $validationErrorTransfer->setMessage($constraintViolation->getMessage());
            $validationErrorTransfer->setPropertyPath($constraintViolation->getPropertyPath());
            $validationErrorTransfer->setInvalidValue($constraintViolation->getInvalidValue());
            $validationErrorTransfer->setRoot($constraintViolation->getRoot());
            $validationResponseTransfer->addValidationError($validationErrorTransfer);
        }

        return $validationResponseTransfer;
    }
}
