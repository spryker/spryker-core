<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer;

interface MerchantAddressesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer[] $merchantAddressesStorageTransfers
     * @param \Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer $restMerchantAddressesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer
     */
    public function mapMerchantAddressesStorageTransferToRestMerchantAddressesAttributesTransfer(
        array $merchantAddressesStorageTransfers,
        RestMerchantAddressesAttributesTransfer $restMerchantAddressesAttributesTransfer
    ): RestMerchantAddressesAttributesTransfer;
}
