<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\Constraint;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 */
class VolumePriceHasBasePriceProductConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @uses \Spryker\Shared\PriceProductOfferVolume\PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE
     *
     * @var string
     */
    protected const VOLUME_PRICE_TYPE = 'volume_prices';

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\Constraint\VolumePriceHasBasePriceProductConstraint $volumePriceHasBasePriceProductConstraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($priceProductOfferCollectionTransfer, Constraint $volumePriceHasBasePriceProductConstraint): void
    {
        if (!$priceProductOfferCollectionTransfer instanceof PriceProductOfferCollectionTransfer) {
            throw new UnexpectedTypeException($priceProductOfferCollectionTransfer, PriceProductOfferCollectionTransfer::class);
        }

        if (!$volumePriceHasBasePriceProductConstraint instanceof VolumePriceHasBasePriceProductConstraint) {
            throw new UnexpectedTypeException($volumePriceHasBasePriceProductConstraint, VolumePriceHasBasePriceProductConstraint::class);
        }

        foreach ($priceProductOfferCollectionTransfer->getPriceProductOffers() as $priceProductOfferIndex => $priceProductOfferTransfer) {
            $this->validatePriceProductOffer(
                $priceProductOfferTransfer,
                $volumePriceHasBasePriceProductConstraint,
                $priceProductOfferIndex,
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTransfer $priceProductOfferTransfer
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\Constraint\VolumePriceHasBasePriceProductConstraint $volumePriceHasBasePriceProductConstraint
     * @param int $priceProductOfferIndex
     *
     * @return void
     */
    protected function validatePriceProductOffer(
        PriceProductOfferTransfer $priceProductOfferTransfer,
        VolumePriceHasBasePriceProductConstraint $volumePriceHasBasePriceProductConstraint,
        int $priceProductOfferIndex
    ): void {
        $priceProductTransfers = $priceProductOfferTransfer
            ->getProductOfferOrFail()
            ->getPrices();

        foreach ($priceProductTransfers as $priceProductIndex => $priceProductTransfer) {
            $this->validatePriceProduct(
                $priceProductTransfer,
                $volumePriceHasBasePriceProductConstraint,
                $priceProductOfferIndex,
                $priceProductIndex,
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\Constraint\VolumePriceHasBasePriceProductConstraint $volumePriceHasBasePriceProductConstraint
     * @param int $priceProductOfferIndex
     * @param int $priceProductIndex
     *
     * @return void
     */
    protected function validatePriceProduct(
        PriceProductTransfer $priceProductTransfer,
        VolumePriceHasBasePriceProductConstraint $volumePriceHasBasePriceProductConstraint,
        int $priceProductOfferIndex,
        int $priceProductIndex
    ): void {
        if (
            $this->isPersistedPrice($priceProductTransfer)
            || $this->isBasePrice($priceProductTransfer)
        ) {
            return;
        }

        $volumePriceProductTransfers = $this->getFactory()
            ->getPriceProductOfferVolumeFacade()
            ->extractVolumePrices([$priceProductTransfer]);

        if (!$volumePriceProductTransfers) {
            return;
        }

        foreach ($volumePriceProductTransfers as $volumePriceIndex => $volumePrice) {
            if ((int)$volumePrice->getVolumeQuantity() === 0) {
                continue;
            }

            $violationPath = $this->createViolationPath(
                $priceProductOfferIndex,
                $priceProductIndex,
                $volumePriceIndex,
            );

            $this->context
                ->buildViolation($volumePriceHasBasePriceProductConstraint->getMessage())
                ->atPath($violationPath)
                ->addViolation();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isPersistedPrice(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getIdPriceProduct() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isBasePrice(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getVolumeQuantity() === 1;
    }

    /**
     * @param int $priceProductOfferIndex
     * @param int $priceProductIndex
     * @param int $volumePriceIndex
     *
     * @return string
     */
    protected function createViolationPath(
        int $priceProductOfferIndex,
        int $priceProductIndex,
        int $volumePriceIndex
    ): string {
        return sprintf(
            '[%s][%d][%s][%s][%d][%s][%s][%s][%d]',
            PriceProductOfferCollectionTransfer::PRICE_PRODUCT_OFFERS,
            $priceProductOfferIndex,
            PriceProductOfferTransfer::PRODUCT_OFFER,
            ProductOfferTransfer::PRICES,
            $priceProductIndex,
            PriceProductTransfer::MONEY_VALUE,
            MoneyValueTransfer::PRICE_DATA,
            static::VOLUME_PRICE_TYPE,
            $volumePriceIndex,
        );
    }
}
