<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantBulkExpanderPluginInterface} instead.
 *
 * Provides extension capabilities for expanding/modification MerchantTransfer data.
 */
interface MerchantExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands a merchant data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function expand(MerchantTransfer $merchantTransfer): MerchantTransfer;
}
