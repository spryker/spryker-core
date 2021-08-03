<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
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
     * @var \Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferValidatorPluginInterface[]
     */
    protected $priceProductOfferValidatorPlugins;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider
     * @param \Spryker\Zed\PriceProductOffer\Business\Validator\PriceProductConstraintProviderInterface $priceProductConstraintProvider
     * @param \Spryker\Zed\PriceProductOffer\Dependency\External\PriceProductOfferToValidationAdapterInterface $validationAdapter
     * @param \Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferValidatorPluginInterface[] $priceProductOfferValidatorPlugins
     */
    public function __construct(
        PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider,
        PriceProductConstraintProviderInterface $priceProductConstraintProvider,
        PriceProductOfferToValidationAdapterInterface $validationAdapter,
        array $priceProductOfferValidatorPlugins
    ) {
        $this->priceProductOfferConstraintProvider = $priceProductOfferConstraintProvider;
        $this->priceProductConstraintProvider = $priceProductConstraintProvider;
        $this->validator = $validationAdapter->createValidator();
        $this->priceProductOfferValidatorPlugins = $priceProductOfferValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateProductOfferPrices(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
    ): ValidationResponseTransfer {
        $validationResponseTransfer = new ValidationResponseTransfer();
        $validationResponseTransfer->setIsSuccess(true);

        $validationResponseTransfer = $this->validatePriceProductOfferTransfers(
            $priceProductOfferCollectionTransfer->getPriceProductOffers(),
            $validationResponseTransfer
        );

        $validationResponseTransfer = $this->executePriceProductOfferValidatorPlugins(
            $priceProductOfferCollectionTransfer,
            $validationResponseTransfer
        );

        return $validationResponseTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductOfferTransfer[] $priceProductOfferTransfers
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    protected function validatePriceProductOfferTransfers(
        ArrayObject $priceProductOfferTransfers,
        ValidationResponseTransfer $validationResponseTransfer
    ): ValidationResponseTransfer {
        $constraintViolationList = $this->validator
            ->startContext()
            ->atPath(sprintf('[%s]', PriceProductOfferCollectionTransfer::PRICE_PRODUCT_OFFERS))
            ->validate(
                $priceProductOfferTransfers,
                $this->priceProductOfferConstraintProvider->getConstraints()
            )->getViolations();

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $validationResponseTransfer
                ->addValidationError(
                    $this->mapConstraintViolationToValidationErrorTransfer($constraintViolation)
                )->setIsSuccess(false);
        }

        foreach ($priceProductOfferTransfers as $priceProductOfferIndex => $priceProductOfferTransfer) {
            $productOfferTransfer = $priceProductOfferTransfer->getProductOfferOrFail();
            $priceProductTransfers = $productOfferTransfer->getPrices();

            foreach ($priceProductTransfers as $priceProductIndex => $priceProductTransfer) {
                $this->validatePriceProduct(
                    $priceProductTransfer,
                    $priceProductOfferIndex,
                    $priceProductIndex,
                    $validationResponseTransfer
                );
            }
        }

        return $validationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    protected function executePriceProductOfferValidatorPlugins(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        ValidationResponseTransfer $validationResponseTransfer
    ): ValidationResponseTransfer {
        foreach ($this->priceProductOfferValidatorPlugins as $priceProductOfferValidatorPlugin) {
            $pluginValidationResponseTransfer = $priceProductOfferValidatorPlugin
                ->validate($priceProductOfferCollectionTransfer);

            foreach ($pluginValidationResponseTransfer->getValidationErrors() as $validationErrorTransfer) {
                $validationResponseTransfer
                    ->addValidationError($validationErrorTransfer)
                    ->setIsSuccess(false);
            }
        }

        return $validationResponseTransfer;
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation
     *
     * @return \Generated\Shared\Transfer\ValidationErrorTransfer
     */
    protected function mapConstraintViolationToValidationErrorTransfer(
        ConstraintViolationInterface $constraintViolation
    ): ValidationErrorTransfer {
        $message = $constraintViolation->getMessage();
        $root = $constraintViolation->getRoot();
        if (is_array($root)) {
            $root = reset($root);
        }

        return (new ValidationErrorTransfer())
            ->setMessage($message)
            ->setPropertyPath($constraintViolation->getPropertyPath())
            ->setInvalidValue($constraintViolation->getInvalidValue())
            ->setRoot($root);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int $priceProductOfferIndex
     * @param int $priceProductIndex
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return void
     */
    protected function validatePriceProduct(
        PriceProductTransfer $priceProductTransfer,
        int $priceProductOfferIndex,
        int $priceProductIndex,
        ValidationResponseTransfer $validationResponseTransfer
    ): void {
        $priceViolations = $this->validator
            ->startContext()
            ->atPath(
                $this->createViolationPath($priceProductOfferIndex, $priceProductIndex)
            )
            ->validate(
                $priceProductTransfer,
                $this->priceProductConstraintProvider->getConstraints()
            )
            ->getViolations();

        /** @var \Symfony\Component\Validator\ConstraintViolation $priceViolation */
        foreach ($priceViolations as $priceViolation) {
            $validationErrorTransfer = $this->mapConstraintViolationToValidationErrorTransfer($priceViolation);
            $validationErrorTransfer->setPropertyPath(
                $this->addPriceTypeToPropertyPath(
                    $priceProductTransfer,
                    $validationErrorTransfer->getPropertyPathOrFail()
                )
            );

            $validationResponseTransfer->addValidationError($validationErrorTransfer);
            $validationResponseTransfer->setIsSuccess(false);
        }
    }

    /**
     * @param int $priceProductOfferIndex
     * @param int $priceProductIndex
     *
     * @return string
     */
    protected function createViolationPath(
        int $priceProductOfferIndex,
        int $priceProductIndex
    ): string {
        return sprintf(
            '[%s][%d][%s][%s][%d]',
            PriceProductOfferCollectionTransfer::PRICE_PRODUCT_OFFERS,
            $priceProductOfferIndex,
            PriceProductOfferTransfer::PRODUCT_OFFER,
            ProductOfferTransfer::PRICES,
            $priceProductIndex
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param string $propertyPath
     *
     * @return string|null
     */
    protected function addPriceTypeToPropertyPath(
        PriceProductTransfer $priceProductTransfer,
        string $propertyPath
    ): ?string {
        $priceTypeName = $priceProductTransfer
            ->getPriceTypeOrFail()
            ->getNameOrFail();

        $moneyValueWithType = sprintf(
            '[%s:%s]',
            PriceProductTransfer::MONEY_VALUE,
            mb_strtolower($priceTypeName)
        );

        $propertyPathWithPriceType = mb_ereg_replace(
            sprintf('\[%s\]', PriceProductTransfer::MONEY_VALUE),
            $moneyValueWithType,
            $propertyPath
        );

        if ($propertyPathWithPriceType === false) {
            return $propertyPath;
        }

        return $propertyPathWithPriceType;
    }
}
