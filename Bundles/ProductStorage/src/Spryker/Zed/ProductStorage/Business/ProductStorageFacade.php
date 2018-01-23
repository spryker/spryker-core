<?php

namespace Spryker\Zed\ProductStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory getFactory()
 */
class ProductStorageFacade extends AbstractFacade implements ProductStorageFacadeInterface
{

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractProducts(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractStorageWriter()->publish($productAbstractIds);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductAbstracts(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractStorageWriter()->unpublish($productAbstractIds);
    }

    /**
     * @param array $productIds
     *
     * @return void
     */
    public function publishConcreteProducts(array $productIds)
    {
        $this->getFactory()->createProductConcreteStorageWriter()->publish($productIds);
    }

    /**
     * @param array $productIds
     *
     * @return void
     */
    public function unpublishConcreteProducts(array $productIds)
    {
        $this->getFactory()->createProductConcreteStorageWriter()->unpublish($productIds);
    }

}
