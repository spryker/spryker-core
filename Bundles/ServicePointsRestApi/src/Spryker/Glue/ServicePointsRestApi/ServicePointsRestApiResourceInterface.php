<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi;

use Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer;

interface ServicePointsRestApiResourceInterface
{
    /**
     * Specification:
     * - Retrieves service types resources filtered by criteria.
     * - Uses `ServiceTypeResourceCriteriaTransfer.serviceTypeResourceConditions.uuids` to filter by service type UUIDs.
     * - Returns `ServiceTypeResourceCollectionTransfer` filled with found `service-types` resources.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer $serviceTypeResourceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer
     */
    public function getServiceTypeResourceCollection(
        ServiceTypeResourceCriteriaTransfer $serviceTypeResourceCriteriaTransfer
    ): ServiceTypeResourceCollectionTransfer;
}
