<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Shared\Kernel\LocatorLocatorInterface;

class ConsoleLocator extends AbstractLocator
{

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $layer = 'Communication';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @var string
     */
    protected $application = 'Zed';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param string $className
     *
     * @throws \Exception
     *
     * @return void
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        throw new \Exception('Please use new for your console plugins');
    }

}
