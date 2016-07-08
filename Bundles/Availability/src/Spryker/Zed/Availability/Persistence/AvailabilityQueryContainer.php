<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method AvailabilityPersistenceFactory getFactory()
 */
class AvailabilityQueryContainer extends AbstractQueryContainer implements AvailabilityQueryContainerInterface
{

    /**
     * @param string $sku
     *
     * @return SpyAvailabilityQuery
     */
    public function querySpyAvailabilityBySku($sku)
    {
        $query = $this->getFactory()->createSpyAvailabilityQuery();
        $query->filterBySku($sku);

        return $query;
    }
}
