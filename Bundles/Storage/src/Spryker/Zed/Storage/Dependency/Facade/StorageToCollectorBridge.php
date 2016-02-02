<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage\Dependency\Facade;

use Spryker\Zed\Collector\Business\CollectorFacade;

class StorageToCollectorBridge implements StorageToCollectorInterface
{

    /**
     * @var CollectorFacade
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
