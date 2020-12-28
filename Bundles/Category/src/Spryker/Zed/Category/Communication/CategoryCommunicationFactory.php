<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication;

use Spryker\Zed\Category\Communication\Updater\CategoryUrlUpdater;
use Spryker\Zed\Category\Communication\Updater\CategoryUrlUpdaterInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 * @method \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface getEntityManager()
 */
class CategoryCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Category\Communication\Updater\CategoryUrlUpdaterInterface
     */
    public function createCategoryUrlUpdater(): CategoryUrlUpdaterInterface
    {
        return new CategoryUrlUpdater();
    }
}
