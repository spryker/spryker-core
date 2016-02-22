<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Dependency\Facade;

use Spryker\Zed\Collector\Business\CollectorFacade;

class StorageToCollectorBridge implements StorageToCollectorInterface
{

    /**
     * @var \Spryker\Zed\Collector\Business\CollectorFacade
     */
    protected $collectorFacade;

    /**
     * @param \Spryker\Zed\Collector\Business\CollectorFacade $collectorFacade
     */
    public function __construct($collectorFacade)
    {
        $this->collectorFacade = $collectorFacade;
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteStorageTimestamps(array $keys = [])
    {
        return $this->collectorFacade->deleteStorageTimestamps($keys);
    }

}
