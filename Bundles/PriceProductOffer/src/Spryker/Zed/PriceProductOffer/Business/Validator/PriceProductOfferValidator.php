<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapterInterface;

class PriceProductOfferValidator implements PriceProductOfferValidatorInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferConstraintProviderInterface
     */
    protected $priceProductOfferConstraintProvider;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider
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
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateProductOfferPrices(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfers): ValidationResponseTransfer
    {
        $validationResponseTransfer = new ValidationResponseTransfer();
        $validationResponseTransfer->setIsSuccess(true);
        $priceProductOfferTransfers = $priceProductOfferCollectionTransfers->getPriceProductOffers();

        $constraintViolationList = $this->validator->validate($priceProductOfferTransfers, $this->priceProductOfferConstraintProvider->getConstraints());

        if (!$constraintViolationList->count()) {
            return $validationResponseTransfer;
        }

        $validationResponseTransfer->setIsSuccess(false);

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
