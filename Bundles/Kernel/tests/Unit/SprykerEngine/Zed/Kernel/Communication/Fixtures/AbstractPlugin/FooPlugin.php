<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\AbstractPlugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

class FooPlugin extends AbstractPlugin
{

    /**
     * @return \SprykerEngine\Zed\Kernel\Business\DependencyContainer\DependencyContainerInterface
     */
    public function getDepCon()
    {
        return $this->dependencyContainer;
    }
}
