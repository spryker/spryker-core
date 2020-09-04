<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Resolver;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;

interface ProductConcreteStorageUrlResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteStorageTransfer $productConcreteStorageTransfer
     *
     * @return string
     */
    public function resolveProductConcreteUrl(ProductConcreteStorageTransfer $productConcreteStorageTransfer): string;
}
