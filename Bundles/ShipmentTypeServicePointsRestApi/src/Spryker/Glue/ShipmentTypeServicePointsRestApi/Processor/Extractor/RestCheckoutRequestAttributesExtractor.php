<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

class RestCheckoutRequestAttributesExtractor implements RestCheckoutRequestAttributesExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return list<int>
     */
    public function extractShipmentMethodIdsFromRestCheckoutRequestAttributesTransfer(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): array {
        $shipmentMethodIds = [];
        foreach ($restCheckoutRequestAttributesTransfer->getShipments() as $restShipmentsTransfer) {
            $shipmentMethodIds[] = $restShipmentsTransfer->getIdShipmentMethodOrFail();
        }

        return array_unique($shipmentMethodIds);
    }
}
