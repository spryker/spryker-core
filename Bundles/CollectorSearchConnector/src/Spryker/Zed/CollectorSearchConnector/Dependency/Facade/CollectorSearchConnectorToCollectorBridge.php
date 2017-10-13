<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorSearchConnector\Dependency\Facade;

class CollectorSearchConnectorToCollectorBridge implements CollectorSearchConnectorToCollectorInterface
{
    /**
     * @var \Spryker\Zed\Collector\Business\CollectorFacadeInterface
     */
    protected $collectorFacade;

    /**
     * @param \Spryker\Zed\Collector\Business\CollectorFacadeInterface $collectorFacade
     */
    public function __construct($collectorFacade)
    {
        $this->collectorFacade = $collectorFacade;
    }

    /**
     * @param array $timestamps
     *
     * @return void
     */
    public function deleteSearchTimestamps(array $timestamps = [])
    {
        $this->collectorFacade->deleteSearchTimestamps($timestamps);
    }
}
