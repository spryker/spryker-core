<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Validator\Rule\ProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface;

class ProductOfferUniquenessValidatorRule implements ProductOfferValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_NOT_UNIQUE = 'product_offer_shipment_type.validation.product_offer_not_unique';

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(ErrorAdderInterface $errorAdder)
    {
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $productOfferTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        $productOfferReferenceIndex = [];

        foreach ($productOfferTransfers as $entityIdentifier => $productOfferTransfer) {
            $productOfferReference = $productOfferTransfer->getProductOfferReferenceOrFail();

            if (!isset($productOfferReferenceIndex[$productOfferReference])) {
                $productOfferReferenceIndex[$productOfferReference] = true;

                continue;
            }

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_NOT_UNIQUE,
            );
        }

        return $errorCollectionTransfer;
    }
}
