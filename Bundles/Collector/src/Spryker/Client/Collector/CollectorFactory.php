<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Collector;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Collector\KeyBuilder\UrlKeyBuilder;
use Spryker\Client\Collector\Matcher\UrlMatcher;
use Spryker\Client\Storage\StorageClient;

class CollectorFactory extends AbstractFactory
{

    /**
     * @return UrlMatcher
     */
    public function createUrlMatcher()
    {
        return new UrlMatcher(
            $this->createUrlKeyBuilder(),
            $this->createKeyValueReader()
        );
    }

    /**
     * @return UrlKeyBuilder
     */
    protected function createUrlKeyBuilder()
    {
        $urlKeyBuilder = new UrlKeyBuilder();

        return $urlKeyBuilder;
    }

    /**
     * @return StorageClient
     */
    protected function createKeyValueReader()
    {
        $keyValueReader = $this->getLocator()->storage()->client();

        return $keyValueReader;
    }

}
