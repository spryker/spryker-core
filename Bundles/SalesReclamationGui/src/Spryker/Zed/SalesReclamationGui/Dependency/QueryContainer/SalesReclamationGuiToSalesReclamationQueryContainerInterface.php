<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Dependency\QueryContainer;

use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItemQuery;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery;

interface SalesReclamationGuiToSalesReclamationQueryContainerInterface
{
    /**
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery
     */
    public function queryReclamations(): SpySalesReclamationQuery;

    /**
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItemQuery
     */
    public function queryReclamationItems(): SpySalesReclamationItemQuery;
}
