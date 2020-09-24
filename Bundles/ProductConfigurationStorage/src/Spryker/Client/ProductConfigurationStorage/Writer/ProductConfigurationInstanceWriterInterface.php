<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Writer;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

interface ProductConfigurationInstanceWriterInterface
{
    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return void
     */
    public function storeProductConfigurationInstanceBySku(
        string $sku,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): void;
}
