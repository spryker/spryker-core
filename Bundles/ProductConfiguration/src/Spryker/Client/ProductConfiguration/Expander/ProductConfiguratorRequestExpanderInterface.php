<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;

interface ProductConfiguratorRequestExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    public function expandProductConfiguratorRequestWithContextData(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer;
}
