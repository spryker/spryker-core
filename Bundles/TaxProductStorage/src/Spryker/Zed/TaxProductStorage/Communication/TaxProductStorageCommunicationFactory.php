<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\TaxProductStorage\Dependency\Facade\TaxProductStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\TaxProductStorage\TaxProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\TaxProductStorage\TaxProductStorageConfig getConfig()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxProductStorage\Business\TaxProductStorageFacadeInterface getFacade()
 */
class TaxProductStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\TaxProductStorage\Dependency\Facade\TaxProductStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): TaxProductStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(TaxProductStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
