<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Collector;

use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Collector\KeyBuilder\UrlKeyBuilder;
use SprykerFeature\Client\Collector\Matcher\UrlMatcher;
use SprykerFeature\Client\Storage\StorageClient;

class CollectorDependencyContainer extends AbstractDependencyContainer
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
