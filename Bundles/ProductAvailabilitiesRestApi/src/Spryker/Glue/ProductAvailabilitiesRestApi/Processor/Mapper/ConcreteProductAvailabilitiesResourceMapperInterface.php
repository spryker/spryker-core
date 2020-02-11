<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer;

interface ConcreteProductAvailabilitiesResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     * @param \Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer $restConcreteProductAvailabilityAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer
     */
    public function mapProductConcreteAvailabilityTransferToRestConcreteProductAvailabilityAttributesTransfer(
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer,
        RestConcreteProductAvailabilityAttributesTransfer $restConcreteProductAvailabilityAttributesTransfer
    ): RestConcreteProductAvailabilityAttributesTransfer;
}
