<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business;

use Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAlternative\Business\ProductAlternativeBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface getEntityManager()
 */
class ProductAlternativeFacade extends AbstractFacade implements ProductAlternativeFacadeInterface
{
    /**
     * TODO: Replace transfer that is returned to proper one
     * TODO: Rewrite the logic
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): SpyProductAlternativeEntityTransfer
    {
        return $this
            ->getFactory()
            ->createProductAlternativeWriter()
            ->createProductAbstractAlternative($idProduct, $idProductAbstractAlternative);
    }

    /**
     * TODO: Replace transfer that is returned to proper one
     * TODO: Rewrite the logic
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): SpyProductAlternativeEntityTransfer
    {
        return $this
            ->getFactory()
            ->createProductAlternativeWriter()
            ->createProductConcreteAlternative($idProduct, $idProductConcreteAlternative);
    }

    // TODO: Add methods to get alternative product
}
