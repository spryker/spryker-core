<?php


namespace Spryker\Zed\ProductValidity\Communication\Plugin;


use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface;

/**
 * @method \Spryker\Zed\ProductValidity\Business\ProductValidityFacadeInterface getFacade()
 */
class ProductValidityReadObserver extends AbstractPlugin implements ProductConcretePluginReadInterface
{
    /**
     * @param ProductConcreteTransfer $productConcreteTransfer
     *
     * @return ProductConcreteTransfer
     */
    public function read(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getFacade()
            ->hydrateProductConcrete($productConcreteTransfer);
    }

}