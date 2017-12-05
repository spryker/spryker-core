<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Dependency\QueryContainer;

interface ProductSetStorageToProductSetQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSet();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetDataQuery
     */
    public function queryProductSetData();

}
