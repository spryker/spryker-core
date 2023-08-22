<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;

interface ServiceTypeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     * @param \Generated\Shared\Transfer\ServiceTypesBackendApiAttributesTransfer $serviceTypesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypesBackendApiAttributesTransfer
     */
    public function mapServiceTypeTransferToServiceTypesBackendApiAttributesTransfer(
        ServiceTypeTransfer $serviceTypeTransfer,
        ServiceTypesBackendApiAttributesTransfer $serviceTypesBackendApiAttributesTransfer
    ): ServiceTypesBackendApiAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServiceTypesBackendApiAttributesTransfer $serviceTypesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function mapServiceTypesBackendApiAttributesTransferToServiceTypeTransfer(
        ServiceTypesBackendApiAttributesTransfer $serviceTypesBackendApiAttributesTransfer,
        ServiceTypeTransfer $serviceTypeTransfer
    ): ServiceTypeTransfer;

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfers
     * @param \Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer $serviceTypeResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer
     */
    public function mapServiceTypeTransfersToServiceTypeResourceCollectionTransfer(
        ArrayObject $serviceTypeTransfers,
        ServiceTypeResourceCollectionTransfer $serviceTypeResourceCollectionTransfer
    ): ServiceTypeResourceCollectionTransfer;
}
