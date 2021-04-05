<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication;

use Spryker\Zed\CategoryGui\CategoryGuiDependencyProvider;
use Spryker\Zed\CategoryGui\Communication\Table\CategoryTable;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
class CategoryGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Table\CategoryTable
     */
    public function createCategoryTable(): CategoryTable
    {
        return new CategoryTable(
            $this->getLocaleFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected function getLocaleFacade(): CategoryGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::FACADE_LOCALE);
    }
}
