<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;

interface ProductConfiguratorRequestDataExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function expand(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
    ): ProductConfiguratorRequestDataTransfer;
}
