<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Dependency\Facade;

class ProductAlternativeGuiToProductFacadeBridge implements ProductAlternativeGuiToProductFacadeInterface
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
     * @param string $searchName
     *
     * @return string[]
     */
    public function suggestProductAbstract(string $searchName): array
    {
        return $this->productFacade
            ->suggestProductAbstract($searchName);
    }

    /**
     * @param string $searchName
     *
     * @return string[]
     */
    public function suggestProductConcrete(string $searchName): array
    {
        return $this->productFacade
            ->suggestProductConcrete($searchName);
    }
}
