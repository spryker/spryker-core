<?php

<<<<<<< HEAD:Bundles/Kernel/src/Spryker/Client/Kernel/ClientLocator.php
namespace Spryker\Client\Kernel;

use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Shared\Kernel\ClassResolver\ClassNotFoundException;
use Spryker\Shared\Kernel\Locator\LocatorException;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Shared\Library\Log;
=======
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Shared\Kernel\Locator\LocatorException;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
>>>>>>> #999 WIP remove factory:Bundles/Kernel/src/Spryker/Client/Kernel/ClientLocator.php

class ClientLocator extends AbstractLocator
{

    const LOCATABLE_SUFFIX = 'Client';

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @var string
     */
    protected $application = 'Client';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param null $className
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        return $this->getClientResolver()->resolve($bundle);

    }

    /**
     * @return ClientResolver
     */
    private function getClientResolver()
    {
        return new ClientResolver();
    }

}
