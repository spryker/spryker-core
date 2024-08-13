<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesPaymentMerchant\Communication\Extractor\SalesOrderItemExtractor;
use Spryker\Zed\SalesPaymentMerchant\Communication\Extractor\SalesOrderItemExtractorInterface;
use Spryker\Zed\SalesPaymentMerchant\Communication\Mapper\SalesOrderMapper;
use Spryker\Zed\SalesPaymentMerchant\Communication\Mapper\SalesOrderMapperInterface;
use Spryker\Zed\SalesPaymentMerchant\Communication\Reader\SalesOrderReader;
use Spryker\Zed\SalesPaymentMerchant\Communication\Reader\SalesOrderReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesFacadeInterface;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantDependencyProvider;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig getConfig()
 * @method \Spryker\Zed\SalesPaymentMerchant\Business\SalesPaymentMerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantEntityManagerInterface getEntityManager()
 */
class SalesPaymentMerchantCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Communication\Reader\SalesOrderReaderInterface
     */
    public function createSalesOrderReader(): SalesOrderReaderInterface
    {
        return new SalesOrderReader($this->getSalesFacade());
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Communication\Extractor\SalesOrderItemExtractorInterface
     */
    public function createSalesOrderItemExtractor(): SalesOrderItemExtractorInterface
    {
        return new SalesOrderItemExtractor();
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Communication\Mapper\SalesOrderMapperInterface
     */
    public function createSalesOrderMapper(): SalesOrderMapperInterface
    {
        return new SalesOrderMapper();
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesPaymentMerchantToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesPaymentMerchantDependencyProvider::FACADE_SALES);
    }
}
