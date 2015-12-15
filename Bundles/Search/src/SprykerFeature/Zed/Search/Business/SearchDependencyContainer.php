<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Business;

use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\Search\Business\Model\Search;
use Spryker\Zed\Search\Business\Model\SearchInstaller;
use Spryker\Zed\Search\SearchConfig;
use Spryker\Zed\Search\SearchDependencyProvider;

/**
 * @method SearchConfig getConfig()
 */
class SearchDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @param MessengerInterface $messenger
     *
     * @return SearchInstaller
     */
    public function createSearchInstaller(MessengerInterface $messenger)
    {
        return new SearchInstaller(
            $this->getConfig()->getInstaller(),
            $messenger
        );
    }

    /**
     * @return Search
     */
    public function createSearch()
    {
        return new Search(
            $this->getProvidedDependency(SearchDependencyProvider::CLIENT_SEARCH)
        );
    }

}
