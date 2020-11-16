<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidProductOfferPriceIdsOwnByMerchantConstraintValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the Valid from value is earlier than Valid to.
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $value
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Constraint\ValidProductOfferPriceIdsOwnByMerchantConstraint $validProductOfferPriceIdsConstraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $validProductOfferPriceIdsConstraint): void
    {
        if (!$value instanceof PriceProductOfferCollectionTransfer) {
            throw new UnexpectedTypeException($value, PriceProductOfferCollectionTransfer::class);
        }

        if (!$validProductOfferPriceIdsConstraint instanceof ValidProductOfferPriceIdsOwnByMerchantConstraint) {
            throw new UnexpectedTypeException($validProductOfferPriceIdsConstraint, ValidProductOfferPriceIdsOwnByMerchantConstraint::class);
        }

        $normalizedDataCollection = [];
        /** @var \Generated\Shared\Transfer\PriceProductOfferTransfer $priceProductOfferTransfer */
        foreach ($value->getPriceProductOffers() as $priceProductOfferTransfer) {
            $normalizedDataCollection[$priceProductOfferTransfer->getProductOffer()->getFkMerchantOrFail()][] = $priceProductOfferTransfer->getIdPriceProductOffer();
        }

        foreach ($normalizedDataCollection as $idMerchant => $priceProductOfferIds) {
            $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
            $priceProductOfferCriteriaTransfer->setPriceProductOfferIds($priceProductOfferIds)
                ->setProductOfferCriteriaFilter((new ProductOfferCriteriaFilterTransfer())->setMerchantIds());

            $validProductOfferPriceIdsConstraint->getFacade()->count($priceProductOfferCriteriaTransfer) === count($priceProductOfferIds)
                ?: $this->context->addViolation($validProductOfferPriceIdsConstraint->getMessage());
        }
    }
}
