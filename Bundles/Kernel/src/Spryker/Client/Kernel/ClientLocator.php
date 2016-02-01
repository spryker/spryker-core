<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Shared\Kernel\AbstractLocator;

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
     * @throws \Spryker\Client\Kernel\ClassResolver\Client\ClientNotFoundException
     *
     * @return AbstractClient
     */
    public function locate($bundle)
    {
        return $this->getClientResolver()->resolve($bundle);
    }

    /**
     * @return \Spryker\Client\Kernel\ClassResolver\Client\ClientResolver
     */
    private function getClientResolver()
    {
        return new ClientResolver();
    }

}
