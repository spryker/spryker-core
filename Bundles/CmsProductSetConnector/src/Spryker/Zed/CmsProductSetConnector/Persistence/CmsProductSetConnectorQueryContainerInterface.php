<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductSetConnector\Persistence;

/**
 * @method \Spryker\Zed\CmsProductSetConnector\Persistence\CmsProductSetConnectorPersistenceFactory getFactory()
 */
interface CmsProductSetConnectorQueryContainerInterface
{

    /**
     * @api
     *
     * @param array $keyList
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSetIdsByKeyList(array $keyList);

}
