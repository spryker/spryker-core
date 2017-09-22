<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Store\Business\Model\StoreMapper;
use Spryker\Zed\Store\Business\Model\StoreReader;
use Spryker\Zed\Store\StoreDependencyProvider;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainer getQueryContainer()
 */
class StoreBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Store\Business\Model\StoreReaderInterface
     */
    public function createStoreReader()
    {
        return new StoreReader(
            $this->getKernelStore(),
            $this->getQueryContainer(),
            $this->createStoreMapper()
        );
    }

    /**
     * @return \Spryker\Zed\Store\Business\Model\StoreMapperInterface
     */
    protected function createStoreMapper()
    {
        return new StoreMapper($this->getKernelStore());
    }

    /**
     * @return \Spryker\Zed\Store\Dependency\StoreToKernelStoreInterface
     */
    protected function getKernelStore()
    {
        return $this->getProvidedDependency(StoreDependencyProvider::KERNEL_STORE);
    }
}
