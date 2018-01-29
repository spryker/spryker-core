<?php

namespace Spryker\Zed\ProductValidity\Business;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductValidity\Business\ProductValidityBusinessFactory getFactory()
 */
class ProductValidityFacade extends AbstractFacade implements ProductValidityFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function checkProductValidityDateRangeAndTouch()
    {
        $this->getFactory()
            ->createProductConcreteSwitcher()
            ->updateProductsValidity();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param ProductConcreteTransfer $productConcreteTransfer
     *
     * @return ProductConcreteTransfer
     */
    public function hydrateProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getFactory()
            ->createProductValidityHydrator()
            ->hydrate($productConcreteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param ProductConcreteTransfer $productConcreteTransfer
     *
     * @return ProductConcreteTransfer
     */
    public function saveProductValidity(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getFactory()
            ->createProductValidityUpdater()
            ->update($productConcreteTransfer);
    }
}