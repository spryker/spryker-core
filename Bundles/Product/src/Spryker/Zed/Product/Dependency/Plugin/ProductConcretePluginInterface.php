<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcretePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function run(ProductConcreteTransfer $productConcreteTransfer);

}
