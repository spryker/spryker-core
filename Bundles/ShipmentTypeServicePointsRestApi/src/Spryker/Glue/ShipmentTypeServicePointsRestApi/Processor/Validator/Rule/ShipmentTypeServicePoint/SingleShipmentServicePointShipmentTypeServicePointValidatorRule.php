<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 *             Use {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\MultiShipmentServicePointShipmentTypeServicePointValidatorRule} instead.
 */
class SingleShipmentServicePointShipmentTypeServicePointValidatorRule implements ShipmentTypeServicePointValidatorRuleInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface
     */
    protected RestErrorMessageCreatorInterface $restErrorMessageCreator;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface
     */
    protected ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface
     */
    protected RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor;

    /**
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface $restErrorMessageCreator
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor
     */
    public function __construct(
        RestErrorMessageCreatorInterface $restErrorMessageCreator,
        ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader,
        RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor
    ) {
        $this->restErrorMessageCreator = $restErrorMessageCreator;
        $this->shipmentTypeStorageReader = $shipmentTypeStorageReader;
        $this->restCheckoutRequestAttributesExtractor = $restCheckoutRequestAttributesExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validate(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        $hasApplicableShipmentTypes = $this->hasApplicableShipmentTypes($restCheckoutRequestAttributesTransfer);

        if (!$hasApplicableShipmentTypes && $restCheckoutRequestAttributesTransfer->getServicePoints()->count() > 0) {
            $restErrorCollectionTransfer->addRestError(
                $this->restErrorMessageCreator->createServicePointShouldNotBeProvidedErrorMessage(),
            );
        }

        if ($hasApplicableShipmentTypes && $restCheckoutRequestAttributesTransfer->getServicePoints()->count() === 0) {
            $restErrorCollectionTransfer->addRestError(
                $this->restErrorMessageCreator->createServicePointNotProvidedErrorMessage(),
            );
        }

        if ($hasApplicableShipmentTypes && $restCheckoutRequestAttributesTransfer->getServicePoints()->count() > 1) {
            $restErrorCollectionTransfer->addRestError(
                $this->restErrorMessageCreator->createOnlyOneServicePointShouldBeSelectedErrorMessage(),
            );
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    protected function hasApplicableShipmentTypes(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): bool {
        $shipmentMethodId = $this->restCheckoutRequestAttributesExtractor
            ->extractShipmentMethodIdFromRestCheckoutRequestAttributesTransfer(
                $restCheckoutRequestAttributesTransfer,
            );

        return (bool)$this
            ->shipmentTypeStorageReader
            ->getApplicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod([$shipmentMethodId]);
    }
}
