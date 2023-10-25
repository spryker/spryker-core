<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Checker;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

interface RestCheckoutRequestAttributesCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    public function hasApplicableShipmentTypes(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool;
}
