<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Dependency\Facade;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ShoppingListToProductFacadeBridge implements ShoppingListToProductFacadeInterface
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
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku): bool
    {
        return $this->productFacade->hasProductConcrete($sku);
    }

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function findProductConcretesBySkus(array $skus): array
    {
        return $this->productFacade->findProductConcretesBySkus($skus);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isProductConcreteActive(ProductConcreteTransfer $productConcreteTransfer): bool
    {
        return $this->productFacade->isProductConcreteActive($productConcreteTransfer);
    }

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete(string $concreteSku): ProductConcreteTransfer
    {
        return $this->productFacade->getProductConcrete($concreteSku);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById(int $idProductAbstract): ?ProductAbstractTransfer
    {
        return $this->productFacade->findProductAbstractById($idProductAbstract);
    }
}
