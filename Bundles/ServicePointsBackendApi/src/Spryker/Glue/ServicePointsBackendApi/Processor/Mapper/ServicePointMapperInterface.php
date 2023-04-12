<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiServicePointsAttributesTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;

interface ServicePointMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param \Generated\Shared\Transfer\ApiServicePointsAttributesTransfer $apiServicePointsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiServicePointsAttributesTransfer
     */
    public function mapServicePointTransferToApiServicePointsAttributesTransfer(
        ServicePointTransfer $servicePointTransfer,
        ApiServicePointsAttributesTransfer $apiServicePointsAttributesTransfer
    ): ApiServicePointsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ApiServicePointsAttributesTransfer $apiServicePointsAttributesTransfer
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function mapApiServicePointsAttributesTransferToServicePointTransfer(
        ApiServicePointsAttributesTransfer $apiServicePointsAttributesTransfer,
        ServicePointTransfer $servicePointTransfer
    ): ServicePointTransfer;
}
