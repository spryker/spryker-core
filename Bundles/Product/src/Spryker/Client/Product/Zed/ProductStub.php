<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product\Zed;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\Product\Dependency\Client\ProductToZedRequestClientInterface;

class ProductStub implements ProductStubInterface
{
    /**
     * @var \Spryker\Client\Product\Dependency\Client\ProductToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\Product\Dependency\Client\ProductToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ProductToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function findProductConcreteIdBySku(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $this->zedRequestClient->call('/product/gateway/find-product-concrete-id-by-sku', $productConcreteTransfer);

        return $productConcreteTransfer;
    }
}
