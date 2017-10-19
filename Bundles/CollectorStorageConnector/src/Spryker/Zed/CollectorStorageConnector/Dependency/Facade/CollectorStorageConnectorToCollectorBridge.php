<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorStorageConnector\Dependency\Facade;

class CollectorStorageConnectorToCollectorBridge implements CollectorStorageConnectorToCollectorInterface
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
    public function deleteStorageTimestamps(array $timestamps)
    {
        $this->collectorFacade->deleteStorageTimestamps($timestamps);
    }
}
