<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantCollectionTransfer;

/**
 * Provides extension capabilities for expanding/modification MerchantTransfer data within MerchantCollectionTransfer.
 */
interface MerchantBulkExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands each `MerchantTransfer` from `MerchantCollectionTransfer` with related data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function expand(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer;
}
