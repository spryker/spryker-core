<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductConnector\Persistence;

/**
 * @method \Spryker\Zed\CmsProductConnector\Persistence\CmsProductSetConnectorPersistenceFactory getFactory()
 */
interface CmsProductConnectorQueryContainerInterface
{

    /**
     * @api
     *
     * @param array|string[] $skuList
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductIdsBySkuList(array $skuList);

}
