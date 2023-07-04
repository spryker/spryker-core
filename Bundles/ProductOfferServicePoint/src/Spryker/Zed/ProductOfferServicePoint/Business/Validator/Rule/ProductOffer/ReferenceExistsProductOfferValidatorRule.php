<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface;

class ReferenceExistsProductOfferValidatorRule implements ProductOfferValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_REFERENCE_NOT_FOUND = 'product_offer_service_point.validation.product_offer_reference_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_PRODUCT_OFFER_REFERENCE = '%product_offer_reference%';

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface
     */
    protected ProductOfferReaderInterface $productOfferReader;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface $productOfferReader
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        ProductOfferReaderInterface $productOfferReader
    ) {
        $this->errorAdder = $errorAdder;
        $this->productOfferReader = $productOfferReader;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $productOfferTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($productOfferTransfers as $entityIdentifier => $productOfferTransfer) {
            if (!$this->hasProductOfferReference($productOfferTransfer)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_REFERENCE_NOT_FOUND,
                    [
                        static::GLOSSARY_KEY_PARAMETER_PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReferenceOrFail(),
                    ],
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return bool
     */
    protected function hasProductOfferReference(ProductOfferTransfer $productOfferTransfer): bool
    {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $this->productOfferReader
            ->getProductOfferCollectionByProductOfferReferences([$productOfferTransfer->getProductOfferReferenceOrFail()])
            ->getProductOffers();

        return $productOfferTransfers->count() > 0;
    }
}
