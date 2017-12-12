<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupCreator;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupDeleter;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupEntityReader;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupExpander;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupReader;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupReducer;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupTouch;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupUpdater;
use Spryker\Zed\ProductGroup\ProductGroupDependencyProvider;

/**
 * @method \Spryker\Zed\ProductGroup\ProductGroupConfig getConfig()
 * @method \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface getQueryContainer()
 */
class ProductGroupBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductGroup\Business\Model\ProductGroupCreatorInterface
     */
    public function createProductGroupCreator()
    {
        return new ProductGroupCreator($this->createProductGroupTouch());
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Business\Model\ProductGroupReaderInterface
     */
    public function createProductGroupReader()
    {
        return new ProductGroupReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Business\Model\ProductGroupUpdaterInterface
     */
    public function createProductGroupUpdater()
    {
        return new ProductGroupUpdater($this->createProductGroupEntityReader(), $this->createProductGroupTouch());
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Business\Model\ProductGroupExpanderInterface
     */
    public function createProductGroupExpander()
    {
        return new ProductGroupExpander($this->createProductGroupEntityReader(), $this->createProductGroupTouch());
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Business\Model\ProductGroupReducerInterface
     */
    public function createProductGroupReducer()
    {
        return new ProductGroupReducer($this->createProductGroupEntityReader(), $this->createProductGroupTouch());
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Business\Model\ProductGroupDeleterInterface
     */
    public function createProductGroupDeleter()
    {
        return new ProductGroupDeleter($this->createProductGroupEntityReader(), $this->createProductGroupTouch());
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Business\Model\ProductGroupTouchInterface
     */
    public function createProductGroupTouch()
    {
        return new ProductGroupTouch($this->getTouchFacade(), $this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Business\Model\ProductGroupEntityReaderInterface
     */
    protected function createProductGroupEntityReader()
    {
        return new ProductGroupEntityReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Dependency\Facade\ProductGroupToTouchInterface
     */
    public function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductGroupDependencyProvider::FACADE_TOUCH);
    }
}
