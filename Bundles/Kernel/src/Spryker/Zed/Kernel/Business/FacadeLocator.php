<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Shared\Kernel\ClassResolver\ClassNotFoundException;
use Spryker\Shared\Kernel\Locator\LocatorException;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Shared\Library\Log;
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
     * @param LocatorLocatorInterface $locator
     * @param string|null $className
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $facadeResolver = new FacadeResolver();
        $facade = $facadeResolver->resolve($bundle);

        try {
            if ($locator->$bundle()->hasQueryContainer()) {
                $facade->setOwnQueryContainer($locator->$bundle()->queryContainer());
            }
        } catch (ClassNotFoundException $e) {
            // TODO remove try-catch when all bundles have a Builder
            Log::log(APPLICATION . ' - ' . $bundle, 'builder_missing.log');
        }

        return $facade;
    }

}
