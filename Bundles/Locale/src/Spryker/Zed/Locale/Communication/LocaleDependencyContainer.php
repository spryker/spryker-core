<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\Locale\Business\LocaleFacade;

class LocaleDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return LocaleFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

}
