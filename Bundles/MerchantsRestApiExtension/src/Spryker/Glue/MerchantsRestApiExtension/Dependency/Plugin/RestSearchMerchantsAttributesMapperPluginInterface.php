<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantSearchTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;

/**
 * Provides ability to map MerchantCategorySearch transfer to RestAttributesTransfer.
 */
interface RestSearchMerchantsAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps MerchantSearchTransfer to RestMerchantsAttributesTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapMerchantSearchTransferToRestMerchantsAttributesTransfer(
        MerchantSearchTransfer $merchantSearchTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer;
}
