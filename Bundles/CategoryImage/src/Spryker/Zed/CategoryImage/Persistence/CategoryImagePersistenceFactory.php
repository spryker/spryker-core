<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Spryker\Zed\CategoryImage\CategoryImageDependencyProvider;
use Spryker\Zed\CategoryImage\Persistence\Propel\Mapper\CategoryImageMapper;
use Spryker\Zed\CategoryImage\Persistence\Propel\Mapper\CategoryImageMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CategoryImage\CategoryImageConfig getConfig()
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface getRepository()
 */
class CategoryImagePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\CategoryImage\Persistence\Propel\Mapper\CategoryImageMapperInterface
     */
    public function createCategoryImageMapper(): CategoryImageMapperInterface
    {
        return new CategoryImageMapper(
            $this->getProvidedDependency(CategoryImageDependencyProvider::FACADE_LOCALE)
        );
    }
}
