<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOptionCartConnector\Business\Model\GroupKeyExpander;
use Spryker\Zed\ProductOptionCartConnector\Business\Model\ProductOptionCartQuantity;
use Spryker\Zed\ProductOptionCartConnector\Business\Model\ProductOptionValidator;
use Spryker\Zed\ProductOptionCartConnector\Business\Model\ProductOptionValidatorInterface;
use Spryker\Zed\ProductOptionCartConnector\Business\Model\ProductOptionValueExpander;
use Spryker\Zed\ProductOptionCartConnector\Business\Validator\ProductOptionValuePriceValidator;
use Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorConfig getConfig()
 */
class ProductOptionCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOptionCartConnector\Business\Model\ProductOptionValueExpanderInterface
     */
    public function createProductOptionValueExpander()
    {
        return new ProductOptionValueExpander(
            $this->getProductOptionFacade(),
            $this->getPriceFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeInterface
     */
    protected function getProductOptionFacade()
    {
        return $this->getProvidedDependency(ProductOptionCartConnectorDependencyProvider::FACADE_PRODUCT_OPTION);
    }

    /**
     * @return \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToPriceFacadeInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductOptionCartConnectorDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ProductOptionCartConnector\Business\Model\ProductOptionCartQuantityInterface
     */
    public function createProductOptionCartQuantity()
    {
        return new ProductOptionCartQuantity();
    }

    /**
     * @return \Spryker\Zed\ProductOptionCartConnector\Business\Model\GroupKeyExpanderInterface
     */
    public function createGroupKeyExpander()
    {
        return new GroupKeyExpander();
    }

    /**
     * @return \Spryker\Zed\ProductOptionCartConnector\Business\Model\ProductOptionValidatorInterface
     */
    public function createProductOptionValidator(): ProductOptionValidatorInterface
    {
        return new ProductOptionValidator($this->getProductOptionFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOptionCartConnector\Business\Validator\ProductOptionValuePriceValidatorInterface
     */
    public function createProductOptionValuePriceValidator()
    {
        return new ProductOptionValuePriceValidator(
            $this->getProductOptionFacade(),
            $this->getPriceFacade()
        );
    }
}
