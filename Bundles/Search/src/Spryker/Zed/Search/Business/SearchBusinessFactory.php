<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Business;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use Spryker\Zed\Search\Business\Model\Search;
use Spryker\Zed\Search\Business\Model\SearchInstaller;
use Spryker\Zed\Search\SearchConfig;
use Spryker\Zed\Search\SearchDependencyProvider;

/**
 * @method SearchConfig getConfig()
 */
class SearchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param MessengerInterface $messenger
     *
     * @return \Spryker\Zed\Search\Business\Model\SearchInstaller
     */
    public function createSearchInstaller(MessengerInterface $messenger)
    {
        return new SearchInstaller(
            $this->getInstallers(),
            $messenger
        );
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Search
     */
    public function createSearch()
    {
        return new Search(
            $this->getProvidedDependency(SearchDependencyProvider::CLIENT_SEARCH)
        );
    }

    /**
     * @return AbstractInstallerPlugin[]
     */
    public function getInstallers()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::INSTALLERS);
    }

}
