<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantRelationshipProductList\Business\CustomerExpander\CustomerExpander;
use Spryker\Zed\MerchantRelationshipProductList\Business\CustomerExpander\CustomerExpanderInterface;
use Spryker\Zed\MerchantRelationshipProductList\Business\ProductList\ProductListReader;
use Spryker\Zed\MerchantRelationshipProductList\Business\ProductList\ProductListReaderInterface;
use Spryker\Zed\MerchantRelationshipProductList\Business\ProductList\ProductListWriter;
use Spryker\Zed\MerchantRelationshipProductList\Business\ProductList\ProductListWriterInterface;
use Spryker\Zed\MerchantRelationshipProductList\Business\Reader\MerchantRelationshipReader;
use Spryker\Zed\MerchantRelationshipProductList\Business\Reader\MerchantRelationshipReaderInterface;
use Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToProductListFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductList\MerchantRelationshipProductListDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\MerchantRelationshipProductListConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListEntityManagerInterface getEntityManager()
 */
class MerchantRelationshipProductListBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipProductList\Business\ProductList\ProductListReaderInterface
     */
    public function createProductListReader(): ProductListReaderInterface
    {
        return new ProductListReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductList\Business\CustomerExpander\CustomerExpanderInterface
     */
    public function createCustomerExpander(): CustomerExpanderInterface
    {
        return new CustomerExpander($this->createProductListReader());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductList\Business\ProductList\ProductListWriterInterface
     */
    public function createProductListWriter(): ProductListWriterInterface
    {
        return new ProductListWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getProductListFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductList\Business\Reader\MerchantRelationshipReaderInterface
     */
    public function createMerchantRelationshipReader(): MerchantRelationshipReaderInterface
    {
        return new MerchantRelationshipReader(
            $this->getRepository(),
            $this->getMerchantRelationshipFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToProductListFacadeInterface
     */
    public function getProductListFacade(): MerchantRelationshipProductListToProductListFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipProductListDependencyProvider::FACADE_PRODUCT_LIST);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): MerchantRelationshipProductListToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipProductListDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }
}
