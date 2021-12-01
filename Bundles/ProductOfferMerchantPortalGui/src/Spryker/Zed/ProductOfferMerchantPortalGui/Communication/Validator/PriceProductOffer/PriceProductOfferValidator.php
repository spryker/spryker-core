<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\External\ProductOfferMerchantPortalGuiToValidationAdapterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PriceProductOfferValidator implements PriceProductOfferValidatorInterface
{
    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferConstraintProviderInterface
     */
    protected $priceProductOfferConstraintProvider;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\External\ProductOfferMerchantPortalGuiToValidationAdapterInterface $validationAdapter
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToValidationAdapterInterface $validationAdapter,
        PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider,
        ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade
    ) {
        $this->validator = $validationAdapter->createValidator();
        $this->priceProductOfferConstraintProvider = $priceProductOfferConstraintProvider;
        $this->priceProductOfferFacade = $priceProductOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validatePriceProductOfferCollection(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
    ): ValidationResponseTransfer {
        $constraintViolationList = $this->validator
            ->validate(
                $priceProductOfferCollectionTransfer,
                $this->priceProductOfferConstraintProvider->getConstraints(),
            );

        if ($constraintViolationList->count() !== 0) {
            return $this->mapConstraintViolationListToValidationResponseTransfer(
                $constraintViolationList,
                new ValidationResponseTransfer(),
            );
        }

        return $this->priceProductOfferFacade
            ->validateProductOfferPrices($priceProductOfferCollectionTransfer);
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $constraintViolationList
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    protected function mapConstraintViolationListToValidationResponseTransfer(
        ConstraintViolationListInterface $constraintViolationList,
        ValidationResponseTransfer $validationResponseTransfer
    ): ValidationResponseTransfer {
        $validationResponseTransfer->setIsSuccess(false);

        foreach ($constraintViolationList as $constraintViolation) {
            $validationError = $this->mapConstraintViolationToValidationErrorTransfer(
                $constraintViolation,
                new ValidationErrorTransfer(),
            );

            $validationResponseTransfer->addValidationError($validationError);
        }

        return $validationResponseTransfer;
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation
     * @param \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationErrorTransfer
     */
    protected function mapConstraintViolationToValidationErrorTransfer(
        ConstraintViolationInterface $constraintViolation,
        ValidationErrorTransfer $validationErrorTransfer
    ): ValidationErrorTransfer {
        return $validationErrorTransfer
            ->setMessage($constraintViolation->getMessage())
            ->setInvalidValue($constraintViolation->getInvalidValue())
            ->setPropertyPath($constraintViolation->getPropertyPath())
            ->setRoot($constraintViolation->getRoot());
    }
}
