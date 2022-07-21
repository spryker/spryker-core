<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business\Validator;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface;
use Spryker\Shared\PriceProductOfferVolume\PriceProductOfferVolumeConfig;
use Spryker\Zed\PriceProductOfferVolume\Dependency\External\PriceProductOfferVolumeToValidationAdapterInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PriceProductOfferVolumeValidator implements PriceProductOfferVolumeValidatorInterface
{
    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @var \Spryker\Zed\PriceProductOfferVolume\Business\Validator\PriceProductOfferConstraintProviderInterface
     */
    protected $priceProductOfferConstraintProvider;

    /**
     * @var \Spryker\Zed\PriceProductOfferVolume\Business\Validator\PriceProductConstraintProviderInterface
     */
    protected $priceProductConstraintProvider;

    /**
     * @var \Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface
     */
    protected $priceProductOfferVolumeService;

    /**
     * @param \Spryker\Zed\PriceProductOfferVolume\Dependency\External\PriceProductOfferVolumeToValidationAdapterInterface $validationAdapter
     * @param \Spryker\Zed\PriceProductOfferVolume\Business\Validator\PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider
     * @param \Spryker\Zed\PriceProductOfferVolume\Business\Validator\PriceProductConstraintProviderInterface $priceProductConstraintProvider
     * @param \Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceInterface $priceProductOfferVolumeService
     */
    public function __construct(
        PriceProductOfferVolumeToValidationAdapterInterface $validationAdapter,
        PriceProductOfferConstraintProviderInterface $priceProductOfferConstraintProvider,
        PriceProductConstraintProviderInterface $priceProductConstraintProvider,
        PriceProductOfferVolumeServiceInterface $priceProductOfferVolumeService
    ) {
        $this->validator = $validationAdapter->createValidator();
        $this->priceProductOfferConstraintProvider = $priceProductOfferConstraintProvider;
        $this->priceProductConstraintProvider = $priceProductConstraintProvider;
        $this->priceProductOfferVolumeService = $priceProductOfferVolumeService;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validate(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
    ): ValidationResponseTransfer {
        $validationResponseTransfer = (new ValidationResponseTransfer())
            ->setIsSuccess(true);

        $constraintViolationList = $this->validator
            ->startContext()
            ->atPath(sprintf('[%s]', PriceProductOfferCollectionTransfer::PRICE_PRODUCT_OFFERS))
            ->validate(
                $priceProductOfferCollectionTransfer->getPriceProductOffers(),
                $this->priceProductOfferConstraintProvider->getConstraints(),
            )
            ->getViolations();

        foreach ($priceProductOfferCollectionTransfer->getPriceProductOffers() as $priceProductOfferIndex => $priceProductOfferTransfer) {
            $priceProductTransfers = $priceProductOfferTransfer->getProductOfferOrFail()->getPrices();

            foreach ($priceProductTransfers as $priceProductIndex => $priceProductTransfer) {
                $volumePriceProductTransfers = $this->priceProductOfferVolumeService
                    ->extractVolumePrices([$priceProductTransfer]);

                $constraintViolationList = $this->validateVolumePrices(
                    $volumePriceProductTransfers,
                    $constraintViolationList,
                    $priceProductOfferIndex,
                    $priceProductIndex,
                );
            }
        }

        if ($constraintViolationList->count() === 0) {
            return $validationResponseTransfer;
        }

        $validationResponseTransfer->setIsSuccess(false);

        return $this->mapConstraintViolationListToValidationResponseTransfer(
            $constraintViolationList,
            $validationResponseTransfer,
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $volumePriceProductTransfers
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $constraintViolationList
     * @param int $priceProductOfferIndex
     * @param int $priceProductIndex
     *
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected function validateVolumePrices(
        array $volumePriceProductTransfers,
        ConstraintViolationListInterface $constraintViolationList,
        int $priceProductOfferIndex,
        int $priceProductIndex
    ): ConstraintViolationListInterface {
        foreach ($volumePriceProductTransfers as $volumePriceIndex => $volumePriceProductTransfer) {
            $violationPath = $this->createViolationPath(
                $volumePriceProductTransfer,
                $priceProductOfferIndex,
                $priceProductIndex,
                $volumePriceIndex,
            );

            $constraintViolationListPriceProduct = $this->validator
                ->startContext()
                ->atPath($violationPath)
                ->validate(
                    $volumePriceProductTransfer,
                    $this->priceProductConstraintProvider->getConstraints(),
                )->getViolations();

            $constraintViolationList->addAll($constraintViolationListPriceProduct);
        }

        return $constraintViolationList;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $volumePriceProductTransfer
     * @param int $priceProductOfferIndex
     * @param int $priceProductIndex
     * @param int $volumePriceIndex
     *
     * @return string
     */
    protected function createViolationPath(
        PriceProductTransfer $volumePriceProductTransfer,
        int $priceProductOfferIndex,
        int $priceProductIndex,
        int $volumePriceIndex
    ): string {
        $moneyValueWithPriceType = sprintf(
            '%s:%s',
            PriceProductTransfer::MONEY_VALUE,
            mb_strtolower($volumePriceProductTransfer->getPriceTypeOrFail()->getNameOrFail()),
        );

        return sprintf(
            '[%s][%d][%s][%s][%d][%s][%s][%s][%d]',
            PriceProductOfferCollectionTransfer::PRICE_PRODUCT_OFFERS,
            $priceProductOfferIndex,
            PriceProductOfferTransfer::PRODUCT_OFFER,
            ProductOfferTransfer::PRICES,
            $priceProductIndex,
            $moneyValueWithPriceType,
            MoneyValueTransfer::PRICE_DATA,
            PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE,
            $volumePriceIndex,
        );
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
        foreach ($constraintViolationList as $constraintViolation) {
            $validationErrorTransfer = $this->mapConstraintViolationToValidationErrorTransfer(
                $constraintViolation,
                new ValidationErrorTransfer(),
            );

            $validationResponseTransfer->addValidationError($validationErrorTransfer);
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
