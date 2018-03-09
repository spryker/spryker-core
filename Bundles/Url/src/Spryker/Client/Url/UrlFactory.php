<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Url;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Url\Matcher\UrlMatcher;
use Spryker\Shared\Url\KeyBuilder\UrlKeyBuilder;

class UrlFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Url\Dependency\Client\UrlToStorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(UrlDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Shared\Url\KeyBuilder\UrlKeyBuilder
     */
    public function createUrlKeyBuilder()
    {
        return new UrlKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Url\Matcher\UrlMatcherInterface
     */
    public function createUrlMatcher()
    {
        return new UrlMatcher(
            $this->createUrlKeyBuilder(),
            $this->getStorageClient()
        );
    }
}
