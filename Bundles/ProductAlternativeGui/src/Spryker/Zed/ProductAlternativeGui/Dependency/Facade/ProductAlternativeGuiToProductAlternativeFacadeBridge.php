<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductAlternativeListTransfer;
use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

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

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function getProductAlternativeListByIdProductConcrete(int $idProductConcrete): ProductAlternativeListTransfer
    {
        return $this->productAlternativeFacade
            ->getProductAlternativeListByIdProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAlternativeByIdProductAlternativeResponse(int $idProductAlternative): ProductAlternativeResponseTransfer
    {
        return $this->productAlternativeFacade
            ->deleteProductAlternativeByIdProductAlternativeResponse(
                $idProductAlternative
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternative(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->productAlternativeFacade
            ->persistProductAlternative($productConcreteTransfer);
    }
}
