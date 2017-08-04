<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerTest\Shared\Kernel\Fixtures;

use Spryker\Shared\Kernel\AbstractLocator;

class MissingPropertyLocator extends AbstractLocator
{

    /**
     * @param string $bundle
     *
     * @return object
     */
    public function locate($bundle)
    {
    }

}
