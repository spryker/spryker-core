<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Search\Business;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SearchDependencyContainer getDependencyContainer
 */
class SearchFacade extends AbstractFacade
{

    /**
     * @param MessengerInterface $messenger
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getDependencyContainer()->createSearchInstaller($messenger)->install();
    }

}
