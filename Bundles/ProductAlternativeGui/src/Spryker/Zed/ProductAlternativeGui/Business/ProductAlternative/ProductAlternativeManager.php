<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface;

class ProductAlternativeManager implements ProductAlternativeManagerInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @param \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
     */
    public function __construct(
        ProductAlternativeGuiToProductAlternativeFacadeInterface $productAlternativeFacade
    ) {
        $this->productAlternativeFacade = $productAlternativeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternatives(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->productAlternativeFacade->persistProductAlternatives($productConcreteTransfer);
    }

    /**
     * @param int $idBaseProduct
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAbstractAlternative(int $idBaseProduct, int $idProductAbstract): ProductAlternativeResponseTransfer
    {
        return $this->productAlternativeFacade
            ->deleteProductAbstractAlternative($idBaseProduct, $idProductAbstract);
    }

    /**
     * @param int $idBaseProduct
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductConcreteAlternative(int $idBaseProduct, int $idProductConcrete): ProductAlternativeResponseTransfer
    {
        return $this->productAlternativeFacade
            ->deleteProductConcreteAlternative($idBaseProduct, $idProductConcrete);
    }
}
