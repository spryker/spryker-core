<?php

namespace SprykerTest\Zed\ProductBarcode\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductBarcodeDataHelper extends Module
{
    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete(): ProductConcreteTransfer
    {
        return (new ProductConcreteBuilder())->build();
    }
}
