<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Dependency\Facade;

class ProductDiscontinuedToProductFacadeAdapter implements ProductDiscontinuedToProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete($idProductConcrete): void
    {
        $this->productFacade->deactivateProductConcrete($idProductConcrete);
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return void
     */
    public function deactivateProductConcretesByConcreteSkus(array $productConcreteSkus): void
    {
        //Added for BC reason
        if (method_exists($this->productFacade, 'deactivateProductConcretesByConcreteSkus')) {
            $this->deactivateProductConcretesByProductConcretes($productConcreteSkus);

            return;
        }

        $this->productFacade->deactivateProductConcretesByConcreteSkus($productConcreteSkus);
    }

    /**
     * @deprecated Added for BC reasons.
     *
     * @param string[] $productConcreteSkus
     *
     * @return void
     */
    protected function deactivateProductConcretesByProductConcretes(array $productConcreteSkus): void
    {
        foreach ($productConcreteSkus as $productConcreteSku) {
            $productConcreteTransfer = $this->productFacade->getProductConcrete($productConcreteSku);
            $this->deactivateProductConcrete($productConcreteTransfer->getIdProductConcrete());
        }
    }
}
