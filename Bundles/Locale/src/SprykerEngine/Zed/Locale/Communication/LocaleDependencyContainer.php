<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
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
