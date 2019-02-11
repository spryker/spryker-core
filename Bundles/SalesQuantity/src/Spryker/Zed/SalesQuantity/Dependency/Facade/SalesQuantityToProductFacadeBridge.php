<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;

/**
 * @deprecated Will be removed without replacement.
 *
 */
class SalesQuantityToProductFacadeBridge implements SalesQuantityToProductFacadeInterface
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
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku): ProductConcreteTransfer
    {
        return $this->productFacade->getProductConcrete($concreteSku);
    }
}
