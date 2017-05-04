<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelPersistenceFactory getFactory()
 */
class ProductLabelQueryContainer extends AbstractQueryContainer implements ProductLabelQueryContainerInterface
{

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelById($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->filterByIdProductLabel($idProductLabel);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelByAbstractProduct($idProductAbstract)
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->useSpyProductLabelProductAbstractQuery()
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryLocalizedAttributesByProductLabel($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createLocalizedAttributesQuery()
            ->filterByFkProductLabel($idProductLabel);
    }

}
