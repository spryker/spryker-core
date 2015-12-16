<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Locale\Business\LocaleFacade;

class LocaleDependencyContainer extends AbstractCommunicationFactory
{

    /**
     * @return LocaleFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

}
