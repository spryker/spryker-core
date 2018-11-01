<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Collector;

use Spryker\Client\Collector\KeyBuilder\UrlKeyBuilder;
use Spryker\Client\Collector\Matcher\UrlMatcher;
use Spryker\Client\Kernel\AbstractFactory;

class CollectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Collector\Matcher\UrlMatcherInterface
     */
    public function createUrlMatcher()
    {
        return new UrlMatcher(
            $this->createUrlKeyBuilder(),
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\Collector\KeyBuilder\UrlKeyBuilder
     */
    protected function createUrlKeyBuilder()
    {
        $urlKeyBuilder = new UrlKeyBuilder();

        return $urlKeyBuilder;
    }
}
