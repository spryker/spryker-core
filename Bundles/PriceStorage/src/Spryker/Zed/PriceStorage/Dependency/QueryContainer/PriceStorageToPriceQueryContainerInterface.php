<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage\Dependency\QueryContainer;

interface PriceStorageToPriceQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryAllPriceProduct();

}
