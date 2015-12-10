<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Collector\Service;

use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Collector\Service\KeyBuilder\UrlKeyBuilder;
use SprykerFeature\Client\Collector\Service\Matcher\UrlMatcher;
use SprykerFeature\Client\Storage\Service\StorageClient;

class CollectorDependencyContainer extends AbstractServiceDependencyContainer
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
