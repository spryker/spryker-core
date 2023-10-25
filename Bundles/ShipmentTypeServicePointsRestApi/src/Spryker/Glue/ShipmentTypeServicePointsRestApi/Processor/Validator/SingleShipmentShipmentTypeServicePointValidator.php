<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 *             Use {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\MultiShipmentShipmentTypeServicePointValidator} instead.
 */
class SingleShipmentShipmentTypeServicePointValidator extends AbstractShipmentTypeServicePointValidator
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return list<int>
     */
    protected function extractShipmentMethodIds(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): array
    {
        return [$restCheckoutRequestAttributesTransfer->getShipmentOrFail()->getIdShipmentMethodOrFail()];
    }
}
