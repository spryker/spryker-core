<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\Model;

use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface;

class ProductSuggester implements ProductSuggesterInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
     */
    public function __construct(
        ProductFacadeInterface $productFacade,
        ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
    ) {
        $this->productFacade = $productFacade;
        $this->productAlternativeFacade = $productAlternativeFacade;
    }

    /**
     * @param string $productName
     * @param int $limit
     *
     * @return string[]
     */
    public function suggestProductNames(string $productName, int $limit = 10): array
    {
        // TODO: Implement suggestProductName() method.

        return [];
    }

    /**
     * @param string $productSku
     * @param int $limit
     *
     * @return string[]
     */
    public function suggestProductSkus(string $productSku, int $limit = 10): array
    {
        // TODO: Implement suggestProductSku() method.

        return [];
    }
}
