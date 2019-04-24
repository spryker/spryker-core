<?php

namespace Spryker\Client\SharedCartsRestApi;

use Spryker\Client\SharedCartsRestApi\Zed\SharedCartsRestApiStub;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToZedRequestClientInterface;

class SharedCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SharedCartsRestApi\Zed\SharedCartsRestApiStubInterface
     */
    public function createZedStub()
    {
        return new SharedCartsRestApiStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToZedRequestClientInterface
     */
    protected function getZedRequestClient(): SharedCartsRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(SharedCartsRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }

}
