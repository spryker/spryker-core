<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability\Zed;

use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
interface AvailabilityStubInterface
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function findProductConcreteAvailability(ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer);
}
