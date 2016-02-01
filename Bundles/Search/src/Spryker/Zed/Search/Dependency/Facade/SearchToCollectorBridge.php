<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Dependency\Facade;

use Spryker\Zed\Collector\Business\CollectorFacade;

class SearchToCollectorBridge implements SearchToCollectorInterface
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
    public function deleteSearchTimestamps(array $keys = [])
    {
        return $this->collectorFacade->deleteSearchTimestamps($keys);
    }

}
