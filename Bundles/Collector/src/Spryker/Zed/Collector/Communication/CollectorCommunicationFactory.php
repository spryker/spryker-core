<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Communication;

use Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface;
use Spryker\Zed\Collector\CollectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Collector\CollectorConfig;

/**
 * @method CollectorConfig getConfig()
 */
class CollectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @deprecated Use getLocaleFacade() instead.
     *
     * @return CollectorToLocaleInterface
     */
    public function createLocaleFacade()
    {
        trigger_error('Deprecated, use getLocaleFacade() instead.', E_USER_DEPRECATED);

        return $this->getLocaleFacade();
    }

    /**
     * @return CollectorToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::FACADE_LOCALE);
    }

}
