<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Business;

use Spryker\Zed\ContentStorage\Business\ContentStorage\ContentStorageWriter;
use Spryker\Zed\ContentStorage\Business\ContentStorage\ContentStorageWriterInterface;
use Spryker\Zed\ContentStorage\ContentStorageDependencyProvider;
use Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ContentStorage\ContentStorageConfig getConfig()
 */
class ContentStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ContentStorage\Business\ContentStorage\ContentStorageWriterInterface
     */
    public function createContentStorage(): ContentStorageWriterInterface
    {
        return new ContentStorageWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getLocaleFacade(),
            $this->getUtilEncoding()
        );
    }

    /**
     * @return \Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ContentStorageToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ContentStorage\Dependency\Service\ContentStorageToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
