<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;

interface MerchantSearchDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Allows to expand merchant search data before saving to Elastica.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    public function expand(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): MerchantSearchCollectionTransfer;
}
