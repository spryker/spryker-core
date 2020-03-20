<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer;

interface MerchantOpeningHourMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     * @param \Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer $restMerchantOpeningHoursAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer
     */
    public function mapMerchantStorageTransferToRestMerchantOpeningHoursAttributesTransfer(
        MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer,
        RestMerchantOpeningHoursAttributesTransfer $restMerchantOpeningHoursAttributesTransfer
    ): RestMerchantOpeningHoursAttributesTransfer;
}
