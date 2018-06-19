<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchBusinessFactory getFactory()
 */
class ProductListSearchFacade extends AbstractFacade implements ProductListSearchFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByConcreteIds(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->createProductAbstractReader()
            ->getProductAbstractIdsByConcreteIds($productConcreteIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        return $this->getFactory()
            ->createProductAbstractReader()
            ->getProductAbstractIdsByCategoryIds($categoryIds);
    }
}
