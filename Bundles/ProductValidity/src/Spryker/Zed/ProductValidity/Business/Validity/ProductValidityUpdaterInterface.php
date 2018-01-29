<?php


namespace Spryker\Zed\ProductValidity\Business\Validity;


use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductValidityUpdaterInterface
{
    /**
     * @param ProductConcreteTransfer $productConcreteTransfer
     *
     * @return ProductConcreteTransfer
     */
    public function update(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}