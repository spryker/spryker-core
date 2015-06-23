<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Locale\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;

class LocaleDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return LocaleFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->locale()->facade();
    }
}
