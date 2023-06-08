<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface;

class HasSingleServicePointProductOfferValidatorRule implements ProductOfferValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_HAS_MULTIPLE_SERVICE_POINTS = 'product_offer_service_point.validation.product_offer_has_multiple_service_points';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_PRODUCT_OFFER_REFERENCE = '%product_offer_reference%';

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        ProductOfferExtractorInterface $productOfferExtractor
    ) {
        $this->errorAdder = $errorAdder;
        $this->productOfferExtractor = $productOfferExtractor;
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
            if (!$this->hasMultipleServicePoints($productOfferTransfer)) {
                continue;
            }

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_PRODUCT_OFFER_HAS_MULTIPLE_SERVICE_POINTS,
                [
                    static::GLOSSARY_KEY_PARAMETER_PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReferenceOrFail(),
                ],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return bool
     */
    protected function hasMultipleServicePoints(ProductOfferTransfer $productOfferTransfer): bool
    {
        $servicePointUuids = $this->productOfferExtractor->extractServicePointUuidsFromProductOfferTransfer($productOfferTransfer);

        return count(array_unique($servicePointUuids)) > 1;
    }
}
