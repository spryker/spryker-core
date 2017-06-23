<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductSetConnector\Persistence;

use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsProductSetConnector\Persistence\CmsProductSetConnectorPersistenceFactory getFactory()
 */
class CmsProductSetConnectorQueryContainer extends AbstractQueryContainer implements CmsProductSetConnectorQueryContainerInterface
{

    /**
     * @api
     *
     * @param array $keyList
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSetIdsByKeyList(array $keyList)
    {
        return $this->getFactory()
            ->getProductSetQueryContainer()
            ->queryProductSet()
            ->select([
                SpyProductSetTableMap::COL_ID_PRODUCT_SET,
                SpyProductSetTableMap::COL_PRODUCT_SET_KEY,
            ])
            ->filterByProductSetKey($keyList, Criteria::IN);
    }

}
