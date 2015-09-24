<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Search\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SearchBusiness;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Search\Business\Model\SearchInstaller;
use SprykerFeature\Zed\Search\SearchConfig;

/**
 * @method SearchBusiness getFactory()
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
        return $this->getFactory()->createModelSearchInstaller(
            $this->getConfig()->getInstaller(),
            $messenger
        );
    }

}
