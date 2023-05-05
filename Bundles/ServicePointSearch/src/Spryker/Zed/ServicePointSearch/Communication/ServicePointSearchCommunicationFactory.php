<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToServicePointFacadeInterface;
use Spryker\Zed\ServicePointSearch\ServicePointSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ServicePointSearch\ServicePointSearchConfig getConfig()
 * @method \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ServicePointSearch\Business\ServicePointSearchFacadeInterface getFacade()
 */
class ServicePointSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToServicePointFacadeInterface
     */
    public function getServicePointFacade(): ServicePointSearchToServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ServicePointSearchDependencyProvider::FACADE_SERVICE_POINT);
    }
}
