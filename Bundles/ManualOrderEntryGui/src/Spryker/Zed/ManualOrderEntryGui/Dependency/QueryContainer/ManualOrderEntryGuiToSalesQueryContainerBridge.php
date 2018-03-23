<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer;

class ManualOrderEntryGuiToSalesQueryContainerBridge implements ManualOrderEntryGuiToSalesQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $orderSourceQueryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $orderSourceQueryContainer
     */
    public function __construct($orderSourceQueryContainer)
    {
        $this->orderSourceQueryContainer = $orderSourceQueryContainer;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpyOrderSourceQuery
     */
    public function queryOrderSource()
    {
        return $this->orderSourceQueryContainer->queryOrderSource();
    }
}
