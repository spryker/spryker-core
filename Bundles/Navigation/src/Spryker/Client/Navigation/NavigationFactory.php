<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Navigation;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Navigation\Storage\NavigationReader;
use Spryker\Shared\Navigation\KeyBuilder\NavigationKeyBuilder;

class NavigationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Navigation\Storage\NavigationReaderInterface
     */
    public function createNavigationReader()
    {
        return new NavigationReader($this->getStorageClient(), $this->createNavigationKeyBuilder());
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(NavigationDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createNavigationKeyBuilder()
    {
        return new NavigationKeyBuilder();
    }
}
