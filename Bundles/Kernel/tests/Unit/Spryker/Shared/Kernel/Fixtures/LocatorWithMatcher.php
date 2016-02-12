<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Kernel\Fixtures;

use Spryker\Shared\Kernel\AbstractLocator;

class LocatorWithMatcher extends AbstractLocator
{

    /**
     * @var string
     */
    protected $bundle = 'foo';

    /**
     * @var string
     */
    protected $layer = 'bar';

    /**
     * @var string
     */
    protected $application = 'baz';

    /**
     * @param string $bundle
     *
     * @return object
     */
    public function locate($bundle)
    {
        return $this;
    }

}
