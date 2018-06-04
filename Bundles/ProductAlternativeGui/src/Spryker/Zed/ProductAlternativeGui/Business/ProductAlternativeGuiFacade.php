<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAlternativeGui\Business\ProductAlternativeGuiBusinessFactory getFactory()
 */
class ProductAlternativeGuiFacade extends AbstractFacade implements ProductAlternativeGuiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $searchName
     *
     * @return string[]
     */
    public function suggestProduct(string $searchName): array
    {
        return $this
            ->getFactory()
            ->createProductSuggester()
            ->suggestProduct($searchName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternatives(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this
            ->getFactory()
            ->createProductAlternativeManager()
            ->persistProductAlternatives($productConcreteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idBaseProduct
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAbstractAlternative(int $idBaseProduct, int $idProductAbstract): ProductAlternativeResponseTransfer
    {
        return $this
            ->getFactory()
            ->createProductAlternativeManager()
            ->deleteProductAbstractAlternative(
                $idBaseProduct,
                $idProductAbstract
            );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idBaseProduct
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductConcreteAlternative(int $idBaseProduct, int $idProductConcrete): ProductAlternativeResponseTransfer
    {
        return $this
            ->getFactory()
            ->createProductAlternativeManager()
            ->deleteProductConcreteAlternative(
                $idBaseProduct,
                $idProductConcrete
            );
    }
}
