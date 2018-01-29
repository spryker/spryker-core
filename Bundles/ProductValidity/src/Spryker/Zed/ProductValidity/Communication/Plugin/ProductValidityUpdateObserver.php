<?php

namespace Spryker\Zed\ProductValidity\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface;

/**
 * @method \Spryker\Zed\ProductValidity\Business\ProductValidityFacadeInterface getFacade()
 */
class ProductValidityUpdateObserver extends AbstractPlugin implements ProductConcreteUpdateObserverInterface
{
    /**
     * @param ProductConcreteTransfer $productConcreteTransfer
     *
     * @return ProductConcreteTransfer
     */
    public function update(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getFacade()
            ->saveProductValidity($productConcreteTransfer);
    }
}