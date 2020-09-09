<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Builder;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;

interface ProductConcreteStorageUrlBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteStorageTransfer $productConcreteStorageTransfer
     *
     * @return string
     */
    public function buildProductConcreteUrl(ProductConcreteStorageTransfer $productConcreteStorageTransfer): string;
}
