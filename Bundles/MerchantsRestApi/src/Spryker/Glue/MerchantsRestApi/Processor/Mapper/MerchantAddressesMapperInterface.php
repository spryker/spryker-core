<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer;

interface MerchantAddressesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer[] $merchantStorageProfileAddressTransfers
     * @param \Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer $restMerchantAddressesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer
     */
    public function mapMerchantStorageProfileAddressTransfersToRestMerchantAddressesAttributesTransfer(
        array $merchantStorageProfileAddressTransfers,
        RestMerchantAddressesAttributesTransfer $restMerchantAddressesAttributesTransfer
    ): RestMerchantAddressesAttributesTransfer;
}
