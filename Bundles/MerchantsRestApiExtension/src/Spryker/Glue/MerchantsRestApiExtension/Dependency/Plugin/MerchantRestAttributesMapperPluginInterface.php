<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;

interface MerchantRestAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps MerchantStorageTransfer to RestMerchantsAttributesTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapMerchantStorageTransferToRestMerchantsAttributesTransfer(
        MerchantStorageTransfer $merchantStorageTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer;
}
