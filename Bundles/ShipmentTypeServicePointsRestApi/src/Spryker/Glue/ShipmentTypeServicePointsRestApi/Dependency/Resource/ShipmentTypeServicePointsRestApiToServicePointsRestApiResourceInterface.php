<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Resource;

use Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer;

interface ShipmentTypeServicePointsRestApiToServicePointsRestApiResourceInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer $serviceTypeResourceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer
     */
    public function getServiceTypeResourceCollection(
        ServiceTypeResourceCriteriaTransfer $serviceTypeResourceCriteriaTransfer
    ): ServiceTypeResourceCollectionTransfer;
}
