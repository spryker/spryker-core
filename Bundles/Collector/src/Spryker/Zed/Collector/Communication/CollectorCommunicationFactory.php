<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Communication;

use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Collector\CollectorDependencyProvider;
use Spryker\Zed\Installer\Business\Model\AbstractInstaller;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Collector\CollectorConfig;

/**
 * @method CollectorConfig getConfig()
 */
class CollectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return AbstractInstaller
     */
    public function getInstallerFacade()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return LocaleFacade
     */
    public function createLocaleFacade()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::FACADE_LOCALE);
    }

}
