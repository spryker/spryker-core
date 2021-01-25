<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityDataFeed\Dependency\QueryContainer;

interface AvailabilityDataFeedToAvailabilityInterface
{
    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Propel\Runtime\ActiveQuery\Criteria
     */
    public function queryAvailabilityWithStockByIdLocale($idLocale);
}
