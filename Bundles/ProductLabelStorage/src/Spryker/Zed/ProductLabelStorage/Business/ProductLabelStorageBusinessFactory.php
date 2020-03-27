<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabelStorage\Business\Writer\ProductLabelDictionaryStorageWriter;
use Spryker\Zed\ProductLabelStorage\Business\Writer\ProductLabelStorageWriter;
use Spryker\Zed\ProductLabelStorage\ProductLabelStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelStorage\ProductLabelStorageConfig getConfig()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 */
class ProductLabelStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductLabelStorage\Business\Writer\ProductLabelDictionaryStorageWriterInterface
     */
    public function createProductLabelDictionaryStorageWriter()
    {
        return new ProductLabelDictionaryStorageWriter(
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getEventBehaviorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelStorage\Business\Writer\ProductLabelStorageWriterInterface
     */
    public function createProductLabelStorageWriter()
    {
        return new ProductLabelStorageWriter(
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getEventBehaviorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
