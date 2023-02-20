<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Store\Plugin\Expander\StoreExpanderInterface;
use Spryker\Client\Store\Plugin\Expander\StoreStoreReferenceExpander;
use Spryker\Shared\Store\Reader\StoreReader;

/**
 * @method \Spryker\Client\Store\StoreConfig getConfig()
 */
class StoreFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Store\Reader\StoreReaderInterface
     */
    public function createStoreReader()
    {
        return new StoreReader(
            $this->getStore(),
            $this->createStoreExpanders(),
        );
    }

    /**
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(StoreDependencyProvider::STORE);
    }

    /**
     * @return array<\Spryker\Client\Store\Plugin\Expander\StoreExpanderInterface>
     */
    protected function createStoreExpanders(): array
    {
        return [
            $this->createStoreReferenceExpander(),
        ];
    }

    /**
     * @return \Spryker\Client\Store\Plugin\Expander\StoreExpanderInterface
     */
    public function createStoreReferenceExpander(): StoreExpanderInterface
    {
        return new StoreStoreReferenceExpander($this->getConfig());
    }
}
