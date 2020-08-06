<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer;
use Generated\Shared\Transfer\RestMerchantAddressTransfer;

class MerchantAddressMapper implements MerchantAddressMapperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer[] $merchantStorageProfileAddressTransfers
     * @param \Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer $restMerchantAddressesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer
     */
    public function mapMerchantStorageProfileAddressTransfersToRestMerchantAddressesAttributesTransfer(
        ArrayObject $merchantStorageProfileAddressTransfers,
        RestMerchantAddressesAttributesTransfer $restMerchantAddressesAttributesTransfer
    ): RestMerchantAddressesAttributesTransfer {
        foreach ($merchantStorageProfileAddressTransfers as $merchantStorageProfileAddressTransfer) {
            $restMerchantAddressTransfer = (new RestMerchantAddressTransfer())->fromArray(
                $merchantStorageProfileAddressTransfer->toArray(),
                true
            );
            $restMerchantAddressesAttributesTransfer->addAddress($restMerchantAddressTransfer);
        }

        return $restMerchantAddressesAttributesTransfer;
    }
}
