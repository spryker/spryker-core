<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListTransfer;
use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAlternative\Business\ProductAlternativeBusinessFactory getFactory()
 */
class ProductAlternativeFacade extends AbstractFacade implements ProductAlternativeFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductAbstractAlternative(int $idProductAbstract, int $idProductAbstractAlternative): ProductAlternativeResponseTransfer
    {
        return $this->getFactory()
            ->createProductAlternativeWriter()
            ->createProductAbstractAlternativeResponse($idProductAbstract, $idProductAbstractAlternative);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductConcreteAlternative(int $idProductConcrete, int $idProductConcreteAlternative): ProductAlternativeResponseTransfer
    {
        return $this->getFactory()
            ->createProductAlternativeWriter()
            ->createProductConcreteAlternativeResponse($idProductConcrete, $idProductConcreteAlternative);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesByIdProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer
    {
        return $this->getFactory()
            ->createProductAlternativeReader()
            ->getProductAlternativesByIdProductConcrete($idProductConcrete);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAlternativeByIdProductAlternative(int $idProductAlternative): ProductAlternativeTransfer
    {
        return $this->getFactory()
            ->createProductAlternativeReader()
            ->getProductAlternativeByIdProductAlternative($idProductAlternative);
    }

    /**
     * {@inheritdoc}
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
            ->createProductAlternativeListManager()
            ->getProductAlternativeListByIdProductConcrete($idProductConcrete);
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
    public function persistProductAlternative(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getFactory()
            ->createProductAlternativeWriter()
            ->persistProductAlternative($productConcreteTransfer);
    }

    /**
     * {@inheritdoc}
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
            ->deleteProductAlternativeByIdProductAlternativeResponse($idProductAlternative);
    }
}
