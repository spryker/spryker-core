<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReader;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriter;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriterInterface;
use Spryker\Zed\ResourceShare\Dependency\Facade\ResourceShareToUuidFacadeInterface;
use Spryker\Zed\ResourceShare\ResourceShareDependencyProvider;

/**
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface getRepository()
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface getEntityManager()
 */
class ResourceShareBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface
     */
    public function createResourceShareReader(): ResourceShareReaderInterface
    {
        return new ResourceShareReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriterInterface
     */
    public function createResourceShareWriter(): ResourceShareWriterInterface
    {
        return new ResourceShareWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getUuidFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Dependency\Facade\ResourceShareToUuidFacadeInterface
     */
    public function getUuidFacade(): ResourceShareToUuidFacadeInterface
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::FACADE_UUID);
    }
}
