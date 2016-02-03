<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Collector;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Collector\KeyBuilder\UrlKeyBuilder;
use Spryker\Client\Collector\Matcher\UrlMatcher;

class CollectorFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Collector\Matcher\UrlMatcher
     */
    public function createUrlMatcher()
    {
        return new UrlMatcher(
            $this->createUrlKeyBuilder(),
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\Collector\KeyBuilder\UrlKeyBuilder
     */
    protected function createUrlKeyBuilder()
    {
        $urlKeyBuilder = new UrlKeyBuilder();

        return $urlKeyBuilder;
    }

    /**
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Client\Storage\StorageClient
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::CLIENT_KV_STORAGE);
    }

}
