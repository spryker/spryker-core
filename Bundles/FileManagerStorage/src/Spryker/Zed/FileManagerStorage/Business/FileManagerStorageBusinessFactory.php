<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Business;

use Spryker\Zed\FileManagerStorage\Business\Storage\FileManagerStorageWriter;
use Spryker\Zed\FileManagerStorage\FileManagerStorageDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageEntityManagerInterface getEntityManager()
 */
class FileManagerStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\FileManagerStorage\Business\Storage\FileManagerStorageWriterInterface
     */
    public function createFileStorageWriter()
    {
        return new FileManagerStorageWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getLocaleFacade(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Zed\FileManagerStorage\Dependency\Facade\FileManagerStorageToLocaleFacadeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(FileManagerStorageDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(FileManagerStorageDependencyProvider::STORE);
    }
}
