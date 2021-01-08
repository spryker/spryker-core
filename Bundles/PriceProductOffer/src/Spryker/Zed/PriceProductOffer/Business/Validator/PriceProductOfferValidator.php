<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapterInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

class PriceProductOfferValidator implements PriceProductOfferValidatorInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferConstraintProviderInterface
     */
    protected $priceProductOfferConstraintProvider;

    /**
     * @var \Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductConstraintProviderInterface
     */
    protected $priceProductConstraintProvider;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider
     * @param \Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductConstraintProviderInterface $priceProductConstraintProvider
     * @param \Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapterInterface $validationAdapter
     */
    public function __construct(
        PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider,
        PriceProductConstraintProviderInterface $priceProductConstraintProvider,
        PriceProductOfferToValidationAdapterInterface $validationAdapter
    ) {
        $this->priceProductOfferConstraintProvider = $priceProductOfferConstraintProvider;
        $this->priceProductConstraintProvider = $priceProductConstraintProvider;
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

        foreach ($priceProductOfferTransfers as $priceProductOfferTransfer) {
            $priceProductTransfers = $priceProductOfferTransfer->getProductOffer()->getPrices();

            foreach ($priceProductTransfers as $row => $priceProductTransfer) {
                $this->validatePriceProduct($priceProductTransfer, $row, $validationResponseTransfer);
            }
        }

        if (!$constraintViolationList->count() && $validationResponseTransfer->getIsSuccess()) {
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

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation
     * @param int $row
     *
     * @return string
     */
    protected function generatePricePropertyPath(
        PriceProductTransfer $priceProductTransfer,
        ConstraintViolationInterface $constraintViolation,
        int $row
    ): string {
        $priceTypeName = $priceProductTransfer->getPriceType()->getName();
        $propertyPath = $constraintViolation->getPropertyPath();

        return sprintf('[%s][%s]%s', (string)$row, mb_strtolower($priceTypeName), $propertyPath);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int $row
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return void
     */
    protected function validatePriceProduct(
        PriceProductTransfer $priceProductTransfer,
        int $row,
        ValidationResponseTransfer $validationResponseTransfer
    ): void {
        $priceViolations = $this->validator->validate($priceProductTransfer, $this->priceProductConstraintProvider->getConstraints());

        foreach ($priceViolations as $priceViolation) {
            $validationErrorTransfer = (new ValidationErrorTransfer())
                ->setMessage($priceViolation->getMessage())
                ->setPropertyPath($this->generatePricePropertyPath($priceProductTransfer, $priceViolation, $row))
                ->setInvalidValue($priceViolation->getInvalidValue())
                ->setRoot($priceViolation->getRoot());

            $validationResponseTransfer->addValidationError($validationErrorTransfer);
            $validationResponseTransfer->setIsSuccess(false);
        }
    }
}
