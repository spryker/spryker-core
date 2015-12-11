<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Spryker\Zed\Propel\Communication\Plugin\Connection;
use Spryker\Shared\Kernel\Locator\LocatorException;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Shared\Kernel\AbstractLocator;

class QueryContainerLocator extends AbstractLocator
{

    const PROPEL_CONNECTION = 'propel connection';

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $layer = 'Persistence';

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
     * @param string|null $className
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        return $this->getQueryContainerResolver()->resolve($bundle);
    }

    /**
     * @param string $bundle
     *
     * @return bool
     */
    public function canLocate($bundle)
    {
        $factory = $this->getFactory($bundle);

        return $factory->exists($bundle . 'QueryContainer');
    }

    /**
     * @return QueryContainerResolver
     */
    private function getQueryContainerResolver()
    {
        return new QueryContainerResolver();
    }

}
