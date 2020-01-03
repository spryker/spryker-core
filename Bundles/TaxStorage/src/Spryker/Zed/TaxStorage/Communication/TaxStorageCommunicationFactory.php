<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\TaxStorage\Dependency\Facade\TaxSetStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\TaxStorage\TaxStorageDependencyProvider;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxStorage\TaxStorageConfig getConfig()
 * @method \Spryker\Zed\TaxStorage\Business\TaxStorageFacadeInterface getFacade()
 */
class TaxStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\TaxStorage\Dependency\Facade\TaxSetStorageToEventBehaviorFacadeBridge
     */
    public function getEventBehaviorFacade(): TaxSetStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(TaxStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
