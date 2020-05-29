<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToSalesReturnFacadeInterface;
use Spryker\Zed\SalesReturnSearch\SalesReturnSearchDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReturnSearch\SalesReturnSearchConfig getConfig()
 * @method \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesReturnSearch\Business\SalesReturnSearchFacadeInterface getFacade()
 */
class SalesReturnSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToSalesReturnFacadeInterface
     */
    public function getSalesReturnFacade(): SalesReturnSearchToSalesReturnFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnSearchDependencyProvider::FACADE_SALES_RETURN);
    }
}
