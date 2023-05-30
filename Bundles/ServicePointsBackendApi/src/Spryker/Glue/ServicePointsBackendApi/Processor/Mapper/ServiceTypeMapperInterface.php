<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;

interface ServiceTypeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     * @param \Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer $apiServiceTypesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer
     */
    public function mapServiceTypeTransferToApiServiceTypesAttributesTransfer(
        ServiceTypeTransfer $serviceTypeTransfer,
        ApiServiceTypesAttributesTransfer $apiServiceTypesAttributesTransfer
    ): ApiServiceTypesAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer $apiServiceTypesAttributesTransfer
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function mapApiServiceTypesAttributesTransferToServiceTypeTransfer(
        ApiServiceTypesAttributesTransfer $apiServiceTypesAttributesTransfer,
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
