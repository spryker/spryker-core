<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ServiceExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface;

class ServiceUniquenessProductOfferValidatorRule implements ProductOfferValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_NOT_UNIQUE = 'product_offer_service_point.validation.service_not_unique';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_PRODUCT_OFFER_REFERENCE = '%product_offer_reference%';

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ServiceExtractorInterface
     */
    protected ServiceExtractorInterface $serviceExtractor;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ServiceExtractorInterface $serviceExtractor
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        ServiceExtractorInterface $serviceExtractor
    ) {
        $this->errorAdder = $errorAdder;
        $this->serviceExtractor = $serviceExtractor;
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
            if (!$this->hasUniqueServices($productOfferTransfer)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SERVICE_NOT_UNIQUE,
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
    protected function hasUniqueServices(ProductOfferTransfer $productOfferTransfer): bool
    {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers */
        $serviceTransfers = $productOfferTransfer->getServices();
        $serviceUuids = $this->serviceExtractor->extractServiceUuidsFromServiceTransfers($serviceTransfers);

        return count($serviceUuids) === count(array_unique($serviceUuids));
    }
}
