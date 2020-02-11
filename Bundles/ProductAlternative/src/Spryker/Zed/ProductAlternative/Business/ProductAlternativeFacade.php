<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business;

use Generated\Shared\Transfer\ProductAlternativeListTransfer;
use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductAlternative\Business\ProductAlternativeBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface getEntityManager()
 */
class ProductAlternativeFacade extends AbstractFacade implements ProductAlternativeFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function getProductAlternativeListByIdProductConcrete(int $idProductConcrete): ProductAlternativeListTransfer
    {
        return $this->getFactory()
            ->createProductAlternativeReader()
            ->getProductAlternativeListByIdProductConcrete($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternative(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getFactory()
            ->createProductAlternativeWriter()
            ->persistProductAlternative($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAlternativeByIdProductAlternative(int $idProductAlternative): ProductAlternativeResponseTransfer
    {
        return $this->getFactory()
            ->createProductAlternativeWriter()
            ->deleteProductAlternativeByIdProductAlternative($idProductAlternative);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return bool
     */
    public function doAllConcreteProductsHaveAlternatives(array $productIds): bool
    {
        return $this->getFactory()
            ->createProductAlternativeReader()
            ->doAllConcreteProductsHaveAlternatives($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(int $idProductConcrete): bool
    {
        return $this->getFactory()
            ->createProductAlternativeReader()
            ->isAlternativeProductApplicable($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return int[]
     */
    public function findProductAbstractIdsWhichConcreteHasAlternative(): array
    {
        return $this->getFactory()
            ->createProductAlternativeReader()
            ->findProductAbstractIdsWhichConcreteHasAlternative();
    }
}
