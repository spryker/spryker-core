<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface;

abstract class AbstractShipmentTypeServicePointValidator implements ShipmentTypeServicePointValidatorInterface
{
    /**
     * @var list<\Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\ShipmentTypeServicePointValidatorRuleInterface>
     */
    protected array $shipmentTypeServicePointValidatorRules;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface
     */
    protected ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader;

    /**
     * @param list<\Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\ShipmentTypeServicePointValidatorRuleInterface> $shipmentTypeServicePointValidatorRules
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
     */
    public function __construct(
        array $shipmentTypeServicePointValidatorRules,
        ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
    ) {
        $this->shipmentTypeServicePointValidatorRules = $shipmentTypeServicePointValidatorRules;
        $this->shipmentTypeStorageReader = $shipmentTypeStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return list<int>
     */
    abstract protected function extractShipmentMethodIds(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validate(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestErrorCollectionTransfer
    {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        $applicableShipmentTypeStorageTransfers = $this
            ->shipmentTypeStorageReader
            ->getApplicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod(
                $this->extractShipmentMethodIds($restCheckoutRequestAttributesTransfer),
            );
        if ($applicableShipmentTypeStorageTransfers === []) {
            return $restErrorCollectionTransfer;
        }
        foreach ($this->shipmentTypeServicePointValidatorRules as $shipmentTypeServicePointValidatorRule) {
            $restErrorCollectionTransfer = $this->expandRestErrorCollection(
                $restErrorCollectionTransfer,
                $shipmentTypeServicePointValidatorRule->validate($restCheckoutRequestAttributesTransfer),
            );
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $targetRestErrorCollectionTransfer
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $sourceRestErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function expandRestErrorCollection(
        RestErrorCollectionTransfer $targetRestErrorCollectionTransfer,
        RestErrorCollectionTransfer $sourceRestErrorCollectionTransfer
    ): RestErrorCollectionTransfer {
        foreach ($sourceRestErrorCollectionTransfer->getRestErrors() as $restErrorMessageTransfer) {
            $targetRestErrorCollectionTransfer->addRestError($restErrorMessageTransfer);
        }

        return $targetRestErrorCollectionTransfer;
    }
}
