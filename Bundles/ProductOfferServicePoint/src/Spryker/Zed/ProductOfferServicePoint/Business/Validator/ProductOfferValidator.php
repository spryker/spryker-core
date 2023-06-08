<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer;

class ProductOfferValidator implements ProductOfferValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ProductOfferValidatorRuleInterface>
     */
    protected array $productOfferValidatorRules;

    /**
     * @param list<\Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ProductOfferValidatorRuleInterface> $productOfferValidatorRules
     */
    public function __construct(array $productOfferValidatorRules)
    {
        $this->productOfferValidatorRules = $productOfferValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer $productOfferServiceCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer
     */
    public function validate(
        ProductOfferServiceCollectionResponseTransfer $productOfferServiceCollectionResponseTransfer
    ): ProductOfferServiceCollectionResponseTransfer {
        foreach ($this->productOfferValidatorRules as $productOfferValidatorRule) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
            $productOfferTransfers = $productOfferServiceCollectionResponseTransfer->getProductOffers();
            $errorCollectionTransfer = $productOfferValidatorRule->validate($productOfferTransfers);

            $productOfferServiceCollectionResponseTransfer = $this->mergeErrors(
                $productOfferServiceCollectionResponseTransfer,
                $errorCollectionTransfer,
            );
        }

        return $productOfferServiceCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer $productOfferServiceCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer
     */
    protected function mergeErrors(
        ProductOfferServiceCollectionResponseTransfer $productOfferServiceCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ProductOfferServiceCollectionResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $productOfferCollectionResponseErrorTransfers */
        $productOfferCollectionResponseErrorTransfers = $productOfferServiceCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $productOfferCollectionResponseErrorTransfers->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $productOfferServiceCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
