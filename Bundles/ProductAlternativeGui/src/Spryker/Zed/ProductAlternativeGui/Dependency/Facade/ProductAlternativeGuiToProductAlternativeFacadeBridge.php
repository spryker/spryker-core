<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Dependency\Facade;

class ProductAlternativeGuiToProductAlternativeFacadeBridge implements ProductAlternativeGuiToProductAlternativeFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface $productAlternativeFacade
     */
    public function __construct($productAlternativeFacade)
    {
        $this->productAlternativeFacade = $productAlternativeFacade;
    }

    // TODO: Add needed methods
}
