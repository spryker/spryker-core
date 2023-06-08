<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ServiceExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface;

class ServiceExistsProductOfferValidatorRule implements ProductOfferValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_UUID_NOT_FOUND = 'product_offer_service_point.validation.service_uuid_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_SERVICE_UUIDS = '%service_uuids%';

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface
     */
    protected ServiceReaderInterface $serviceReader;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ServiceExtractorInterface
     */
    protected ServiceExtractorInterface $serviceExtractor;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ServiceExtractorInterface $serviceExtractor
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface $serviceReader
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        ProductOfferExtractorInterface $productOfferExtractor,
        ServiceExtractorInterface $serviceExtractor,
        ServiceReaderInterface $serviceReader,
        ErrorAdderInterface $errorAdder
    ) {
        $this->productOfferExtractor = $productOfferExtractor;
        $this->serviceExtractor = $serviceExtractor;
        $this->serviceReader = $serviceReader;
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

        foreach ($productOfferTransfers as $entityIdentifier => $productOfferTransfer) {
            /** @var list<string> $productOfferServiceUuids */
            $productOfferServiceUuids = array_unique($this->productOfferExtractor->extractServiceUuidsFromProductOfferTransfers(new ArrayObject([$productOfferTransfer])));

            if (!$productOfferServiceUuids) {
                continue;
            }

            $serviceCollectionTransfer = $this->serviceReader->getServiceCollectionByServiceUuids($productOfferServiceUuids);
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers */
            $serviceTransfers = $serviceCollectionTransfer->getServices();

            if ($serviceTransfers->count() === count($productOfferServiceUuids)) {
                continue;
            }

            $missingServiceUuids = array_diff(
                $productOfferServiceUuids,
                $this->serviceExtractor->extractServiceUuidsFromServiceTransfers($serviceTransfers),
            );

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_SERVICE_UUID_NOT_FOUND,
                [
                    static::GLOSSARY_KEY_PARAMETER_SERVICE_UUIDS => implode(', ', array_unique($missingServiceUuids)),
                ],
            );
        }

        return $errorCollectionTransfer;
    }
}
