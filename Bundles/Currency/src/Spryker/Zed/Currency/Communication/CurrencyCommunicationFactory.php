<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication;

use Spryker\Zed\Currency\CurrencyDependencyProvider;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface getRepository()
 * @method \Spryker\Zed\Currency\Business\CurrencyFacadeInterface getFacade()
 */
class CurrencyCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface
     */
    public function getStoreFacade(): CurrencyToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::FACADE_STORE);
    }
}
