<?php

namespace Spryker\Zed\ProductStorage\Business;

interface ProductStorageFacadeInterface
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractProducts(array $productAbstractIds);

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductAbstracts(array $productAbstractIds);

    /**
     * @param array $productIds
     *
     * @return void
     */
    public function publishConcreteProducts(array $productIds);

    /**
     * @param array $productIds
     *
     * @return void
     */
    public function unpublishConcreteProducts(array $productIds);
}
