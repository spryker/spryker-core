<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel;

use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Shared\Kernel\Locator\LocatorException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

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
     * @param null|string $className
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
