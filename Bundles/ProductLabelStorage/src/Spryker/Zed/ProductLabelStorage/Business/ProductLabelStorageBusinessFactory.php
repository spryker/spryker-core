<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabelStorage\Business\Storage\ProductLabelDictionaryStorageWriter;
use Spryker\Zed\ProductLabelStorage\Business\Storage\ProductLabelStorageWriter;
use Spryker\Zed\ProductLabelStorage\ProductLabelStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelStorage\ProductLabelStorageConfig getConfig()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 */
class ProductLabelStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductLabelStorage\Business\Storage\ProductLabelDictionaryStorageWriterInterface
     */
    public function createProductLabelDictionaryStorageWriter()
    {
        return new ProductLabelDictionaryStorageWriter(
            $this->getQueryContainer(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelStorage\Business\Storage\ProductLabelStorageWriterInterface
     */
    public function createProductLabelStorageWriter()
    {
        return new ProductLabelStorageWriter(
            $this->getQueryContainer(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::STORE);
    }
}
