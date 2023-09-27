<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;

interface CheckoutDataResponseAttributesExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function expandCheckoutDataResponseAttributesWithSelectedServicePoints(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer;
}
