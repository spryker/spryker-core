<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
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
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesByIdProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer
    {
        return $this->productAlternativeFacade
            ->getProductAlternativesByIdProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAlternativeByIdProductAlternative(int $idProductAlternative): ProductAlternativeTransfer
    {
        return $this->productAlternativeFacade
            ->getProductAlternativeByIdProductAlternative($idProductAlternative);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductAbstractAlternative(int $idProductAbstract, int $idProductAbstractAlternative): ProductAlternativeResponseTransfer
    {
        return $this->productAlternativeFacade
            ->createProductAbstractAlternative(
                $idProductAbstract,
                $idProductAbstractAlternative
            );
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductConcreteAlternative(int $idProductConcrete, int $idProductConcreteAlternative): ProductAlternativeResponseTransfer
    {
        return $this->productAlternativeFacade
            ->createProductConcreteAlternative(
                $idProductConcrete,
                $idProductConcreteAlternative
            );
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
            ->deleteProductAbstractAlternative(
                $idBaseProduct,
                $idProductAbstract
            );
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
            ->deleteProductConcreteAlternative(
                $idBaseProduct,
                $idProductConcrete
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternatives(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->productAlternativeFacade
            ->persistProductAlternatives($productConcreteTransfer);
    }
}
