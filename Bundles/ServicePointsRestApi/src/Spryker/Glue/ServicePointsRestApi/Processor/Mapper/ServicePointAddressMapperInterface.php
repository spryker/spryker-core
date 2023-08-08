<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestServicePointAddressesAttributesTransfer;
use Generated\Shared\Transfer\ServicePointAddressStorageTransfer;

interface ServicePointAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressStorageTransfer $servicePointAddressStorageTransfer
     * @param \Generated\Shared\Transfer\RestServicePointAddressesAttributesTransfer $restServicePointAddressesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestServicePointAddressesAttributesTransfer
     */
    public function mapServicePointAddressStorageTransferToRestServicePointAddressesAttributesTransfer(
        ServicePointAddressStorageTransfer $servicePointAddressStorageTransfer,
        RestServicePointAddressesAttributesTransfer $restServicePointAddressesAttributesTransfer
    ): RestServicePointAddressesAttributesTransfer;
}
