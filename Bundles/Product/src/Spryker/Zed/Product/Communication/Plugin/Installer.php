<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Communication\Plugin;

use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Communication\ProductCommunicationFactory;

/**
 * @method ProductCommunicationFactory getFactory()
 * @method ProductFacade getFacade()
 */
class Installer extends AbstractInstallerPlugin
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install($this->messenger);
    }

}
