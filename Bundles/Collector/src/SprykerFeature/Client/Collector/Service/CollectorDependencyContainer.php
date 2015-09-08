<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Collector\Service;

use Generated\Client\Ide\FactoryAutoCompletion\CollectorService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Collector\Service\KeyBuilder\UrlKeyBuilder;
use SprykerFeature\Client\Collector\Service\Matcher\UrlMatcher;
use SprykerFeature\Client\Storage\Service\StorageClient;

/**
 * @method CollectorService getFactory()
 */
class CollectorDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return UrlMatcher
     */
    public function createUrlMatcher()
    {
        return $this->getFactory()->createMatcherUrlMatcher(
            $this->createUrlKeyBuilder(),
            $this->createKeyValueReader()
        );
    }

    /**
     * @return UrlKeyBuilder
     */
    protected function createUrlKeyBuilder()
    {
        $urlKeyBuilder = $this->getFactory()->createKeyBuilderUrlKeyBuilder();

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
