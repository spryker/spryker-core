<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductRelation\Business\Relation\ProductRelationActivator;
use Spryker\Zed\ProductRelation\Business\Relation\ProductRelationReader;
use Spryker\Zed\ProductRelation\Business\Relation\ProductRelationWriter;
use Spryker\Zed\ProductRelation\Business\Updater\ProductRelationUpdater;
use Spryker\Zed\ProductRelation\ProductRelationDependencyProvider;

/**
 * @method \Spryker\Zed\ProductRelation\ProductRelationConfig getConfig()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface getQueryContainer()
 */
class ProductRelationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\ProductRelationWriterInterface
     */
    public function createProductRelationWriter()
    {
        return new ProductRelationWriter(
            $this->getTouchFacade(),
            $this->getQueryContainer(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\ProductRelationReaderInterface
     */
    public function createProductRelationReader()
    {
        return new ProductRelationReader($this->getQueryContainer(), $this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Relation\ProductRelationActivatorInterface
     */
    public function createProductRelationActivator()
    {
        return new ProductRelationActivator($this->getQueryContainer(), $this->getTouchFacade());
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\Updater\ProductRelationUpdaterInterface
     */
    public function createProductRelationUpdater()
    {
        return new ProductRelationUpdater(
            $this->getQueryContainer(),
            $this->getUtilEncodingService(),
            $this->createProductRelationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
