<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Business;

use Spryker\Zed\ContentStorage\Business\ContentStorage\ContentStorage;
use Spryker\Zed\ContentStorage\ContentStorageDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ContentStorage\ContentStorageConfig getConfig()
 */
class ContentStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ContentStorage\Business\ContentStorage\ContentStorageInterface
     */
    public function createContentStorage()
    {
        return new ContentStorage(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToLocaleFacadeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::FACADE_LOCALE);
    }
}
