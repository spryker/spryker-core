<?php

namespace SprykerTest\Zed\ProductBarcode\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;

class ProductBarcodeDataHelper extends Module
{
    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete()
    {
        return (new ProductConcreteBuilder())->build();
    }
}
