<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToSalesReturnFacadeInterface;
use Spryker\Zed\SalesReturnPageSearch\SalesReturnPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReturnPageSearch\SalesReturnPageSearchConfig getConfig()
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesReturnPageSearch\Business\SalesReturnPageSearchFacadeInterface getFacade()
 */
class SalesReturnPageSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToSalesReturnFacadeInterface
     */
    public function getSalesReturnFacade(): SalesReturnPageSearchToSalesReturnFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnPageSearchDependencyProvider::FACADE_SALES_RETURN);
    }
}
