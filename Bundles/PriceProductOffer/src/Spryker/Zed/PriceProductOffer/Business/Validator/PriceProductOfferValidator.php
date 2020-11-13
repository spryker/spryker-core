<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator;

use Generated\Shared\Transfer\ProductOfferErrorTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface;

class PriceProductOfferValidator implements PriceProductOfferValidatorInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(PriceProductOfferToPriceProductFacadeInterface $priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferResponseTransfer
     */
    public function validatePrices(ProductOfferTransfer $productOfferTransfer): ProductOfferResponseTransfer
    {
        $priceProductValidationResponseTransfer = $this->priceProductFacade
            ->validatePrices($productOfferTransfer->getPrices());

        $productOfferResponseTransfer = (new ProductOfferResponseTransfer())
            ->setIsSuccessful($priceProductValidationResponseTransfer->getSuccessful());

        if (!$priceProductValidationResponseTransfer->getSuccessful()) {
            foreach ($priceProductValidationResponseTransfer->getErrors() as $priceProductValidationErrorTransfer) {
                $productOfferResponseTransfer->addError(
                    (new ProductOfferErrorTransfer())->fromArray($priceProductValidationErrorTransfer->toArray())
                );
            }
        }

        return $productOfferResponseTransfer;
    }
}
