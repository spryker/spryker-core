<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Validator\Rule\ProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface;

class ShipmentTypeExistsProductOfferValidatorRule implements ProductOfferValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_UUID_NOT_FOUND = 'product_offer_shipment_type.validation.shipment_type_uuid_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_SHIPMENT_TYPE_UUIDS = '%shipment_type_uuids%';

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface
     */
    protected ShipmentTypeExtractorInterface $shipmentTypeExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface
     */
    protected ShipmentTypeReaderInterface $shipmentTypeReader;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface $shipmentTypeExtractor
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        ProductOfferExtractorInterface $productOfferExtractor,
        ShipmentTypeExtractorInterface $shipmentTypeExtractor,
        ShipmentTypeReaderInterface $shipmentTypeReader,
        ErrorAdderInterface $errorAdder
    ) {
        $this->productOfferExtractor = $productOfferExtractor;
        $this->shipmentTypeExtractor = $shipmentTypeExtractor;
        $this->shipmentTypeReader = $shipmentTypeReader;
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

        $shipmentTypeUuids = array_unique($this->productOfferExtractor->extractShipmentTypeUuidsFromProductOfferTransfers($productOfferTransfers));

        if ($shipmentTypeUuids === []) {
            return $errorCollectionTransfer;
        }

        $persistedShipmentTypeUuidsGroupedByProductOfferReference = $this->getPersistedShipmentTypeUuidsGroupedByProductOfferReference(
            $productOfferTransfers,
            $shipmentTypeUuids,
        );

        foreach ($productOfferTransfers as $entityIdentifier => $productOfferTransfer) {
            /** @var list<string> $shipmentTypeUuids */
            $shipmentTypeUuids = array_unique($this->productOfferExtractor->extractShipmentTypeUuidsFromProductOfferTransfers(new ArrayObject([$productOfferTransfer])));
            if (!$shipmentTypeUuids) {
                continue;
            }

            $persistedShipmentTypeUuids = $persistedShipmentTypeUuidsGroupedByProductOfferReference[$productOfferTransfer->getProductOfferReferenceOrFail()];

            if (count($persistedShipmentTypeUuids) === count($shipmentTypeUuids)) {
                continue;
            }

            $missingShipmentTypeUuids = array_diff($shipmentTypeUuids, $persistedShipmentTypeUuids);

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_UUID_NOT_FOUND,
                [
                    static::GLOSSARY_KEY_PARAMETER_SHIPMENT_TYPE_UUIDS => implode(', ', array_unique($missingShipmentTypeUuids)),
                ],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     * @param list<string> $shipmentTypeUuids
     *
     * @return array<string, array<string>>
     */
    protected function getPersistedShipmentTypeUuidsGroupedByProductOfferReference(
        ArrayObject $productOfferTransfers,
        array $shipmentTypeUuids
    ): array {
        $persistedShipmentTypeIndex = $this->getPersistedShipmentTypeIndex($shipmentTypeUuids);

        $persistedShipmentTypeUuidsGroupedByProductOfferReference = [];
        foreach ($productOfferTransfers as $productOfferTransfer) {
            $productOfferReference = $productOfferTransfer->getProductOfferReferenceOrFail();
            $persistedShipmentTypeUuidsGroupedByProductOfferReference[$productOfferReference] = [];

            foreach ($productOfferTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
                $shipmentTypeUuid = $shipmentTypeTransfer->getUuidOrFail();

                if (isset($persistedShipmentTypeIndex[$shipmentTypeUuid])) {
                    $persistedShipmentTypeUuidsGroupedByProductOfferReference[$productOfferReference][$shipmentTypeUuid] = $shipmentTypeUuid;
                }
            }
        }

        return $persistedShipmentTypeUuidsGroupedByProductOfferReference;
    }

    /**
     * @param list<string> $shipmentTypeUuids
     *
     * @return array<string, true>
     */
    protected function getPersistedShipmentTypeIndex(array $shipmentTypeUuids): array
    {
        $shipmentTypeCollectionTransfer = $this->shipmentTypeReader->getShipmentTypeCollectionByShipmentTypeUuids($shipmentTypeUuids);

        $persistedShipmentTypeIndex = [];
        foreach ($shipmentTypeCollectionTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $persistedShipmentTypeIndex[$shipmentTypeTransfer->getUuidOrFail()] = true;
        }

        return $persistedShipmentTypeIndex;
    }
}
