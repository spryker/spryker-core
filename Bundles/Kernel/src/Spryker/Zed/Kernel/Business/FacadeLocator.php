<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Shared\Kernel\Locator\LocatorException;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;

class FacadeLocator extends AbstractLocator
{

    const FACADE_SUFFIX = 'Facade';

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $layer = 'Business';

    /**
     * @var string
     */
    protected $application = 'Zed';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @param string $bundle
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle)
    {
        $facadeResolver = new FacadeResolver();
        $facade = $facadeResolver->resolve($bundle);

        return $facade;
    }

}
