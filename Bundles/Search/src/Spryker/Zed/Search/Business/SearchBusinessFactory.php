<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Search\Business\Model\Search;
use Spryker\Zed\Search\Business\Model\SearchInstaller;
use Spryker\Zed\Search\SearchDependencyProvider;

/**
 * @method \Spryker\Zed\Search\SearchConfig getConfig()
 */
class SearchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
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
     * @return \Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin[]
     */
    public function getInstallers()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::INSTALLERS);
    }

}
