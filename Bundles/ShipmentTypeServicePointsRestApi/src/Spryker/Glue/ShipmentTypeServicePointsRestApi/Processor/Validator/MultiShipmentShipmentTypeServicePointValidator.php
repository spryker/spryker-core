<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface;

class MultiShipmentShipmentTypeServicePointValidator extends AbstractShipmentTypeServicePointValidator
{
    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface
     */
    protected RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor;

    /**
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor
     * @param list<\Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\ShipmentTypeServicePointValidatorRuleInterface> $shipmentTypeServicePointValidatorRules
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
     */
    public function __construct(
        RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor,
        array $shipmentTypeServicePointValidatorRules,
        ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
    ) {
        $this->restCheckoutRequestAttributesExtractor = $restCheckoutRequestAttributesExtractor;

        parent::__construct($shipmentTypeServicePointValidatorRules, $shipmentTypeStorageReader);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return list<int>
     */
    protected function extractShipmentMethodIds(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): array
    {
        return $this
            ->restCheckoutRequestAttributesExtractor
            ->extractShipmentMethodIdsFromRestCheckoutRequestAttributesTransfer(
                $restCheckoutRequestAttributesTransfer,
            );
    }
}
