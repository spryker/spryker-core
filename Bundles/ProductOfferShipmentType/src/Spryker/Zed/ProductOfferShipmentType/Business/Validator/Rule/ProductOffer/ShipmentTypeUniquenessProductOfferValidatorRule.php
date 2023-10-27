<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Validator\Rule\ProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface;

class ShipmentTypeUniquenessProductOfferValidatorRule implements ProductOfferValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_NOT_UNIQUE = 'product_offer_shipment_type.validation.shipment_type_not_unique';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_PRODUCT_OFFER_REFERENCE = '%product_offer_reference%';

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface
     */
    protected ShipmentTypeExtractorInterface $shipmentTypeExtractor;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface $shipmentTypeExtractor
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        ShipmentTypeExtractorInterface $shipmentTypeExtractor
    ) {
        $this->errorAdder = $errorAdder;
        $this->shipmentTypeExtractor = $shipmentTypeExtractor;
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
            if (!$this->hasUniqueShipmentTypes($productOfferTransfer)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_NOT_UNIQUE,
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
    protected function hasUniqueShipmentTypes(ProductOfferTransfer $productOfferTransfer): bool
    {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers */
        $shipmentTypeTransfers = $productOfferTransfer->getShipmentTypes();
        $shipmentTypeUuids = $this->shipmentTypeExtractor->extractShipmentTypeUuidsFromShipmentTypeTransfers($shipmentTypeTransfers);

        return count($shipmentTypeUuids) === count(array_unique($shipmentTypeUuids));
    }
}
