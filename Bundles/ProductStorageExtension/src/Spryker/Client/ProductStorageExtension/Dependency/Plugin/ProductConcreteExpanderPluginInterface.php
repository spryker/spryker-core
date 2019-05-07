<?php

namespace Spryker\Client\ProductStorageExtension\Dependency\Plugin;


use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteExpanderPluginInterface
{
    public function expand(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
