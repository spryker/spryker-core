<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Deleter;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferValidatorInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface;

class PriceDeleter implements PriceDeleterInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_ERROR_PRICE_PRODUCT_HAS_VOLUME_PRICES = 'Price product with quantity 1 cannot be deleted when there are volume prices for the same store and currency.';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    protected ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface
     */
    protected ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferValidatorInterface
     */
    protected PriceProductOfferValidatorInterface $priceProductOfferValidator;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\PriceProductOfferValidatorInterface $priceProductOfferValidator
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade,
        ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService,
        PriceProductOfferValidatorInterface $priceProductOfferValidator
    ) {
        $this->priceProductOfferFacade = $priceProductOfferFacade;
        $this->priceProductVolumeService = $priceProductVolumeService;
        $this->priceProductOfferValidator = $priceProductOfferValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function deletePriceByQuantity(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        int $quantity
    ): ValidationResponseTransfer {
        $priceProductOfferCollectionTransfer = $this->deletePriceByQuantityFromPriceProductOfferCollection(
            $priceProductOfferCollectionTransfer,
            $quantity,
        );

        $validationResponseTransfer = $this->validatePriceProductOfferCollection(
            $priceProductOfferCollectionTransfer,
            $quantity,
        );

        if (!$validationResponseTransfer->getIsSuccess()) {
            return $validationResponseTransfer;
        }

        $this->executeDeletion($priceProductOfferCollectionTransfer, $quantity);

        return $validationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer
     */
    protected function deletePriceByQuantityFromPriceProductOfferCollection(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        int $quantity
    ): PriceProductOfferCollectionTransfer {
        foreach ($priceProductOfferCollectionTransfer->getPriceProductOffers() as $priceProductOfferTransfer) {
            $productOfferTransfer = $priceProductOfferTransfer->getProductOfferOrFail();
            foreach ($productOfferTransfer->getPrices() as $priceProductTransfer) {
                if ($quantity === 1) {
                    $this->deleteBasePriceFromPriceProductOfferCollection($priceProductTransfer);

                    continue;
                }

                $this->deleteVolumePriceFromPriceProductOfferCollection($priceProductTransfer, $quantity);
            }
        }

        return $priceProductOfferCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function deleteBasePriceFromPriceProductOfferCollection(
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $priceProductTransfer
            ->setIdPriceProduct(null)
            ->setVolumeQuantity(null)
            ->getMoneyValueOrFail()
                ->setGrossAmount(null)
                ->setNetAmount(null);

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function deleteVolumePriceFromPriceProductOfferCollection(
        PriceProductTransfer $priceProductTransfer,
        int $quantity
    ): PriceProductTransfer {
        $volumePriceProductTransferToDelete = (new PriceProductTransfer())->setVolumeQuantity($quantity);

        return $this->priceProductVolumeService
            ->deleteVolumePrice($priceProductTransfer, $volumePriceProductTransferToDelete);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param int $quantity
     *
     * @return void
     */
    protected function executeDeletion(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        int $quantity
    ): void {
        if ($quantity === 1) {
            $this->priceProductOfferFacade->deleteProductOfferPrices($priceProductOfferCollectionTransfer);

            return;
        }

        foreach ($priceProductOfferCollectionTransfer->getPriceProductOffers() as $priceProductOfferTransfer) {
            $this->priceProductOfferFacade->saveProductOfferPrices($priceProductOfferTransfer->getProductOfferOrFail());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    protected function validatePriceProductOfferCollection(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        int $quantity
    ): ValidationResponseTransfer {
        $validationResponseTransfer = (new ValidationResponseTransfer())
            ->setIsSuccess(true);

        foreach ($priceProductOfferCollectionTransfer->getPriceProductOffers() as $priceProductOfferTransfer) {
            $productOfferTransfer = $priceProductOfferTransfer->getProductOfferOrFail();
            foreach ($productOfferTransfer->getPrices() as $priceProductTransfer) {
                if ($quantity === 1 && $this->priceProductVolumeService->hasVolumePrices($priceProductTransfer)) {
                    $validationResponseTransfer
                        ->setIsSuccess(false)
                        ->addValidationError(
                            (new ValidationErrorTransfer())
                                ->setMessage(static::MESSAGE_ERROR_PRICE_PRODUCT_HAS_VOLUME_PRICES),
                        );
                }
            }
        }

        $this->executePriceProductOfferValidator($priceProductOfferCollectionTransfer, $validationResponseTransfer);

        return $validationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $generalValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    protected function executePriceProductOfferValidator(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        ValidationResponseTransfer $generalValidationResponseTransfer
    ): ValidationResponseTransfer {
        $validationResponseTransfer = $this->priceProductOfferValidator
            ->validatePriceProductOfferCollection($priceProductOfferCollectionTransfer);

        $generalValidationResponseTransfer->setIsSuccess(
            $generalValidationResponseTransfer->getIsSuccess() && $validationResponseTransfer->getIsSuccess(),
        );

        foreach ($validationResponseTransfer->getValidationErrors() as $validationErrorTransfer) {
            $generalValidationResponseTransfer->addValidationError($validationErrorTransfer);
        }

        return $generalValidationResponseTransfer;
    }
}
