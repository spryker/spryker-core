<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Table;

interface OffersTableQueryBuilderInterface
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function buildQuery();
}
