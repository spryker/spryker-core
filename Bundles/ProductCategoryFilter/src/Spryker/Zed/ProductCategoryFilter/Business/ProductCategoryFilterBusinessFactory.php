<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterCreator;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterDeleter;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterReader;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterUpdater;

/**
 * @method \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryFilter\ProductCategoryFilterConfig getConfig()
 */
class ProductCategoryFilterBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterCreatorInterface
     */
    public function createProductCategoryFilterCreator()
    {
        return new ProductCategoryFilterCreator();
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterReaderInterface
     */
    public function createProductCategoryFilterReader()
    {
        return new ProductCategoryFilterReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterUpdaterInterface
     */
    public function createProductCategoryFilterUpdater()
    {
        return new ProductCategoryFilterUpdater($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterDeleterInterface
     */
    public function createProductCategoryFilterDeleter()
    {
        return new ProductCategoryFilterDeleter($this->getQueryContainer());
    }
}
