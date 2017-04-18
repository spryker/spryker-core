<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupCreator;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupDeleter;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupReader;
use Spryker\Zed\ProductGroup\Business\Model\ProductGroupUpdater;

/**
 * @method \Spryker\Zed\ProductGroup\ProductGroupConfig getConfig()
 * @method \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainer getQueryContainer()
 */
class ProductGroupBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductGroup\Business\Model\ProductGroupCreatorInterface
     */
    public function createProductGroupCreator()
    {
        return new ProductGroupCreator();
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
        return new ProductGroupUpdater($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Business\Model\ProductGroupDeleterInterface
     */
    public function createProductGroupDeleter()
    {
        return new ProductGroupDeleter($this->getQueryContainer());
    }

}
