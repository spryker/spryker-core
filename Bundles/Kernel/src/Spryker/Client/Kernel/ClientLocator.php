<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Shared\Kernel\AbstractLocator;
use Spryker\Shared\Kernel\Locator\LocatorException;

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
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle)
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
