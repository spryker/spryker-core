<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerTest\Client\Kernel\Fixtures\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;

class FooPlugin extends AbstractPlugin
{

    /**
     * @return \Spryker\Client\Kernel\AbstractPlugin
     */
    public function getFactory()
    {
        return parent::getFactory();
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    public function getClient()
    {
        return parent::getClient();
    }

}
