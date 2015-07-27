<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Communication;

use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\Collector\CollectorDependencyProvider;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

class CollectorDependencyContainer extends AbstractCommunicationDependencyContainer
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
