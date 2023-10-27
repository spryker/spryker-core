<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer;

class ProductOfferValidator implements ProductOfferValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\ProductOfferShipmentType\Business\Validator\Rule\ProductOffer\ProductOfferValidatorRuleInterface>
     */
    protected array $productOfferValidatorRules;

    /**
     * @param list<\Spryker\Zed\ProductOfferShipmentType\Business\Validator\Rule\ProductOffer\ProductOfferValidatorRuleInterface> $productOfferValidatorRules
     */
    public function __construct(array $productOfferValidatorRules)
    {
        $this->productOfferValidatorRules = $productOfferValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer $productOfferShipmentTypeCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer
     */
    public function validate(
        ProductOfferShipmentTypeCollectionResponseTransfer $productOfferShipmentTypeCollectionResponseTransfer
    ): ProductOfferShipmentTypeCollectionResponseTransfer {
        foreach ($this->productOfferValidatorRules as $productOfferValidatorRule) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
            $productOfferTransfers = $productOfferShipmentTypeCollectionResponseTransfer->getProductOffers();
            $errorCollectionTransfer = $productOfferValidatorRule->validate($productOfferTransfers);

            $productOfferShipmentTypeCollectionResponseTransfer = $this->mergeErrors(
                $productOfferShipmentTypeCollectionResponseTransfer,
                $errorCollectionTransfer,
            );
        }

        return $productOfferShipmentTypeCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer $productOfferShipmentTypeCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer
     */
    protected function mergeErrors(
        ProductOfferShipmentTypeCollectionResponseTransfer $productOfferShipmentTypeCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ProductOfferShipmentTypeCollectionResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $productOfferCollectionResponseErrorTransfers */
        $productOfferCollectionResponseErrorTransfers = $productOfferShipmentTypeCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $productOfferCollectionResponseErrorTransfers->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $productOfferShipmentTypeCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
