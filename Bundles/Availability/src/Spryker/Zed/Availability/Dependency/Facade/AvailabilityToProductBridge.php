<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency\Facade;

class AvailabilityToProductBridge implements AvailabilityToProductInterface
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
     * @param string $productConcreteSku
     *
     * @return string
     */
    public function getAbstractSkuFromProductConcrete($productConcreteSku)
    {
        return $this->productFacade->getAbstractSkuFromProductConcrete($productConcreteSku);
    }

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete(string $concreteSku)
    {
        return $this->productFacade->getProductConcrete($concreteSku);
    }
}
