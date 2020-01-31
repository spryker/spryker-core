<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer;

interface AbstractProductAvailabilitiesResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     * @param \Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer $restAbstractProductAvailabilityAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer
     */
    public function mapProductAbstractAvailabilityTransferToRestAbstractProductAvailabilityAttributesTransfer(
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer,
        RestAbstractProductAvailabilityAttributesTransfer $restAbstractProductAvailabilityAttributesTransfer
    ): RestAbstractProductAvailabilityAttributesTransfer;
}
