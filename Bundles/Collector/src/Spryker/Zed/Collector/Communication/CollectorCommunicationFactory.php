<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Communication;

use Spryker\Zed\Collector\CollectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Collector\CollectorConfig getConfig()
 */
class CollectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::FACADE_LOCALE);
    }

}
