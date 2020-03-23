<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper;
use Spryker\Zed\ProductLabelStorage\Business\Storage\ProductLabelDictionaryStorageWriter;
use Spryker\Zed\ProductLabelStorage\Business\Storage\ProductLabelDictionaryStorageWriterInterface;
use Spryker\Zed\ProductLabelStorage\Business\Storage\ProductLabelStorageWriter;
use Spryker\Zed\ProductLabelStorage\ProductLabelStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelStorage\ProductLabelStorageConfig getConfig()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepository getRepository()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManager getEntityManager()
 */
class ProductLabelStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductLabelStorage\Business\Storage\ProductLabelDictionaryStorageWriterInterface
     */
    public function createProductLabelDictionaryStorageWriter(): ProductLabelDictionaryStorageWriterInterface
    {
        return new ProductLabelDictionaryStorageWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getProductLabelFacade(),
            $this->createProductLabelDictionaryItemMapper()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelStorage\Business\Storage\ProductLabelStorageWriterInterface
     */
    public function createProductLabelStorageWriter()
    {
        return new ProductLabelStorageWriter(
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelBridge
     */
    public function getProductLabelFacade()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper
     */
    public function createProductLabelDictionaryItemMapper()
    {
        return new ProductLabelDictionaryItemMapper();
    }
}
