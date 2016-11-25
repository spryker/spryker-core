<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundlePersistenceFactory getFactory()
 */
class ProductBundleQueryContainer extends AbstractQueryContainer implements ProductBundleQueryContainerInterface
{

    /**
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery
     */
    public function queryBundledProduct($idProductConcrete)
    {
        return $this->getFactory()
            ->createProductBundleQuery()
            ->filterByFkBundledProduct($idProductConcrete);
    }
}
