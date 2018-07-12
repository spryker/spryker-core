<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerTest\Service\Kernel\Fixtures\Plugin;

use Spryker\Service\Kernel\AbstractPlugin;

class FooPlugin extends AbstractPlugin
{
    /**
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    public function getFactory()
    {
        return parent::getFactory();
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function getService()
    {
        return parent::getService();
    }
}
