<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterCreator;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterCreatorInterface;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterDeleter;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterDeleterInterface;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterReader;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterReaderInterface;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterUpdater;
use Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterUpdaterInterface;

/**
 * @method \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainer getQueryContainer()
 */
class ProductCategoryFilterBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return ProductCategoryFilterCreatorInterface
     */
    public function createProductCategoryFilterCreator()
    {
        return new ProductCategoryFilterCreator();
    }

    /**
     *
     * @return ProductCategoryFilterReaderInterface
     */
    public function createProductCategoryFilterReader()
    {
        return new ProductCategoryFilterReader($this->getQueryContainer());
    }

    /**
     * @return ProductCategoryFilterUpdaterInterface
     */
    public function createProductCategoryFilterUpdater()
    {
        return new ProductCategoryFilterUpdater($this->getQueryContainer());
    }

    /**
     * @return ProductCategoryFilterDeleterInterface
     */
    public function createProductCategoryFilterDeleter()
    {
        return new ProductCategoryFilterDeleter($this->getQueryContainer());
    }
}
