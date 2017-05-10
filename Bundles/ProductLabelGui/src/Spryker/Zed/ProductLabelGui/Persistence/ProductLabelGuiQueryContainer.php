<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiPersistenceFactory getFactory()
 */
class ProductLabelGuiQueryContainer extends AbstractQueryContainer implements ProductLabelGuiQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabels()
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery();
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelByName($name)
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->filterByName($name);
    }

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

}
