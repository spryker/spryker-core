<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestServicePointsAttributesTransfer;
use Generated\Shared\Transfer\ServicePointSearchTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;

interface ServicePointMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchTransfer $servicePointSearchTransfer
     * @param \Generated\Shared\Transfer\RestServicePointsAttributesTransfer $restServicePointsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestServicePointsAttributesTransfer
     */
    public function mapServicePointSearchTransferToRestServicePointsAttributesTransfer(
        ServicePointSearchTransfer $servicePointSearchTransfer,
        RestServicePointsAttributesTransfer $restServicePointsAttributesTransfer
    ): RestServicePointsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer
     * @param \Generated\Shared\Transfer\RestServicePointsAttributesTransfer $restServicePointsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestServicePointsAttributesTransfer
     */
    public function mapServicePointStorageTransferToRestServicePointsAttributesTransfer(
        ServicePointStorageTransfer $servicePointStorageTransfer,
        RestServicePointsAttributesTransfer $restServicePointsAttributesTransfer
    ): RestServicePointsAttributesTransfer;
}
