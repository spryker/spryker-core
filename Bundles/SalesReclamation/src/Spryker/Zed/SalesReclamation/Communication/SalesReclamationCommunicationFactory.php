<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface;
use Spryker\Zed\SalesReclamation\SalesReclamationDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesReclamation\SalesReclamationConfig getConfig()
 */
class SalesReclamationCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesReclamationToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesReclamationDependencyProvider::FACADE_SALES);
    }
}
