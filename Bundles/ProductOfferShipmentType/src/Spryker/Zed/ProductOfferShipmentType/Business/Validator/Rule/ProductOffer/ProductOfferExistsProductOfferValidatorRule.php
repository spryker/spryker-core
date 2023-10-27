<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Validator\Rule\ProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface;

class ProductOfferExistsProductOfferValidatorRule implements ProductOfferValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_REFERENCE_NOT_FOUND = 'product_offer_shipment_type.validation.product_offer_reference_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_PRODUCT_OFFER_REFERENCE = '%product_offer_reference%';

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReaderInterface
     */
    protected ProductOfferReaderInterface $productOfferReader;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReaderInterface $productOfferReader
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

        $persistedProductOfferTransfers = $this->getPersistedProductOffers($productOfferTransfers);
        $productOfferReferencesIndexedByProductOfferReference = $this->getProductOfferReferencesIndexedByProductOfferReference(
            $persistedProductOfferTransfers,
        );

        foreach ($productOfferTransfers as $entityIdentifier => $productOfferTransfer) {
            if (!isset($productOfferReferencesIndexedByProductOfferReference[$productOfferTransfer->getProductOfferReferenceOrFail()])) {
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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function getPersistedProductOffers(ArrayObject $productOfferTransfers): ArrayObject
    {
        $productOfferReferences = array_values($this->getProductOfferReferencesIndexedByProductOfferReference($productOfferTransfers));

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $this->productOfferReader
            ->getProductOfferCollectionByProductOfferReferences($productOfferReferences)
            ->getProductOffers();

        return $productOfferTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return array<string, string>
     */
    protected function getProductOfferReferencesIndexedByProductOfferReference(ArrayObject $productOfferTransfers): array
    {
        $productOfferReferencesIndexedByProductOfferReference = [];
        foreach ($productOfferTransfers as $productOfferTransfer) {
            $productOfferReference = $productOfferTransfer->getProductOfferReferenceOrFail();
            $productOfferReferencesIndexedByProductOfferReference[$productOfferReference] = $productOfferReference;
        }

        return $productOfferReferencesIndexedByProductOfferReference;
    }
}
