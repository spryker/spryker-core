<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SearchHttp\Business\Model\ConfigDeleter;
use Spryker\Zed\SearchHttp\Business\Model\ConfigDeleterInterface;
use Spryker\Zed\SearchHttp\Business\Model\ConfigWriter;
use Spryker\Zed\SearchHttp\Business\Model\ConfigWriterInterface;
use Spryker\Zed\SearchHttp\Dependency\Facade\SearchHttpToStoreFacadeInterface;
use Spryker\Zed\SearchHttp\SearchHttpDependencyProvider;

/**
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpEntityManagerInterface getEntityManager()()
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpRepositoryInterface getRepository()
 * @method \Spryker\Zed\SearchHttp\SearchHttpConfig getConfig()
 */
class SearchHttpBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SearchHttp\Business\Model\ConfigWriterInterface
     */
    public function createConfigWriter(): ConfigWriterInterface
    {
        return new ConfigWriter($this->getEntityManager(), $this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\SearchHttp\Business\Model\ConfigDeleterInterface
     */
    public function createConfigDeleter(): ConfigDeleterInterface
    {
        return new ConfigDeleter($this->getEntityManager(), $this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\SearchHttp\Dependency\Facade\SearchHttpToStoreFacadeInterface
     */
    public function getStoreFacade(): SearchHttpToStoreFacadeInterface
    {
        return $this->getProvidedDependency(SearchHttpDependencyProvider::FACADE_STORE);
    }
}
