<?php

namespace SprykerFeature\Zed\Sales\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;

/**
 * @method SalesBusiness getFactory()
 */
class SalesDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return Internal\Install
     */
    public function getInstaller()
    {
        return $this->getFactory()->createInternalInstall();
    }
}
