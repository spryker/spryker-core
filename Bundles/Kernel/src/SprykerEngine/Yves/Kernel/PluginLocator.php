<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Shared\Kernel\Locator\LocatorException;
use Spryker\Shared\Kernel\LocatorLocatorInterface;

class PluginLocator extends AbstractLocator
{

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Yves\\Kernel\\\Factory';

    /**
     * @var string
     */
    protected $application = 'Yves';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param string|null $className
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        return $factory->create(ucfirst($className), $factory, $locator);
    }

}
