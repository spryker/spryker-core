<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Dependency\QueryContainer;

use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItemQuery;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery;

class SalesReclamationGuiToSalesReclamationQueryContainerBridge implements SalesReclamationGuiToSalesReclamationQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface
     */
    protected $salesReclamationQueryContainer;

    /**
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface $salesReclamationQueryContainer
     */
    public function __construct($salesReclamationQueryContainer)
    {
        $this->salesReclamationQueryContainer = $salesReclamationQueryContainer;
    }

    /**
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery
     */
    public function queryReclamations(): SpySalesReclamationQuery
    {
        return $this->salesReclamationQueryContainer->queryReclamations();
    }

    /**
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItemQuery
     */
    public function queryReclamationItems(): SpySalesReclamationItemQuery
    {
        return $this->salesReclamationQueryContainer->queryReclamationItems();
    }
}
