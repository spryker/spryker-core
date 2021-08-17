<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;

interface ProductConfigurationInstanceCartChangeExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithProductConfigurationInstance(
        CartChangeTransfer $cartChangeTransfer,
        array $params = []
    ): CartChangeTransfer;
}
