<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\SprykerEngine\Yves\Kernel\Fixtures\AbstractPlugin\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerEngine\Yves\Kernel\Communication\Factory;

class FooPlugin extends AbstractPlugin
{

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return parent::getFactory();
    }

}
