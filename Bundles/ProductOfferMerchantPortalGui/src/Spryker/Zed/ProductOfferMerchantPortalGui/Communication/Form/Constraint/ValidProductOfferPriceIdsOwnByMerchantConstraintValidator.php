<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 */
class ValidProductOfferPriceIdsOwnByMerchantConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the merchant owns product offer prices.
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer|mixed $value
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Constraint\ValidProductOfferPriceIdsOwnByMerchantConstraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof PriceProductOfferCollectionTransfer) {
            throw new UnexpectedTypeException($value, PriceProductOfferCollectionTransfer::class);
        }

        if (!$constraint instanceof ValidProductOfferPriceIdsOwnByMerchantConstraint) {
            throw new UnexpectedTypeException($constraint, ValidProductOfferPriceIdsOwnByMerchantConstraint::class);
        }

        $normalizedDataCollection = [];
        /** @var \Generated\Shared\Transfer\PriceProductOfferTransfer $priceProductOfferTransfer */
        foreach ($value->getPriceProductOffers() as $priceProductOfferTransfer) {
            $productOfferTransfer = $priceProductOfferTransfer->getProductOfferOrFail();
            /** @var int $idPriceProductOffer */
            $idPriceProductOffer = $priceProductOfferTransfer->getIdPriceProductOffer();
            $normalizedDataCollection[$productOfferTransfer->getMerchantReferenceOrFail()][] = $idPriceProductOffer;
        }

        foreach ($normalizedDataCollection as $merchantReference => $priceProductOfferIds) {
            $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
            $priceProductOfferCriteriaTransfer->setPriceProductOfferIds($priceProductOfferIds)
                ->setProductOfferCriteria((new ProductOfferCriteriaTransfer())->setMerchantIds());

            $validCount = $this->getFactory()
                ->getPriceProductOfferFacade()
                ->count($priceProductOfferCriteriaTransfer);

            if ($validCount !== count($priceProductOfferIds)) {
                $this->context->addViolation($constraint->getMessage());
            }
        }
    }
}
