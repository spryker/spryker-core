<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Business;

use Spryker\Zed\CategoryDiscountConnector\Business\Checker\CategoryDecisionRuleChecker;
use Spryker\Zed\CategoryDiscountConnector\Business\Checker\CategoryDecisionRuleCheckerInterface;
use Spryker\Zed\CategoryDiscountConnector\Business\Reader\CategoryReader;
use Spryker\Zed\CategoryDiscountConnector\Business\Reader\CategoryReaderInterface;
use Spryker\Zed\CategoryDiscountConnector\Business\Reader\DiscountableItemReader;
use Spryker\Zed\CategoryDiscountConnector\Business\Reader\DiscountableItemReaderInterface;
use Spryker\Zed\CategoryDiscountConnector\Business\Reader\ProductCategoryReader;
use Spryker\Zed\CategoryDiscountConnector\Business\Reader\ProductCategoryReaderInterface;
use Spryker\Zed\CategoryDiscountConnector\CategoryDiscountConnectorDependencyProvider;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToCategoryFacadeInterface;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToDiscountFacadeInterface;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface;
use Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToProductCategoryFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryDiscountConnector\CategoryDiscountConnectorConfig getConfig()
 */
class CategoryDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryDiscountConnector\Business\Reader\DiscountableItemReaderInterface
     */
    public function createDiscountableItemReader(): DiscountableItemReaderInterface
    {
        return new DiscountableItemReader(
            $this->createCategoryDecisionRuleChecker(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryDiscountConnector\Business\Checker\CategoryDecisionRuleCheckerInterface
     */
    public function createCategoryDecisionRuleChecker(): CategoryDecisionRuleCheckerInterface
    {
        return new CategoryDecisionRuleChecker(
            $this->getDiscountFacade(),
            $this->createProductCategoryReader(),
            $this->createCategoryReader(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryDiscountConnector\Business\Reader\CategoryReaderInterface
     */
    public function createCategoryReader(): CategoryReaderInterface
    {
        return new CategoryReader(
            $this->getCategoryFacade(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryDiscountConnector\Business\Reader\ProductCategoryReaderInterface
     */
    public function createProductCategoryReader(): ProductCategoryReaderInterface
    {
        return new ProductCategoryReader(
            $this->getProductCategoryFacade(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToLocaleFacadeInterface
     */
    public function getLocaleFacade(): CategoryDiscountConnectorToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CategoryDiscountConnectorDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToDiscountFacadeInterface
     */
    public function getDiscountFacade(): CategoryDiscountConnectorToDiscountFacadeInterface
    {
        return $this->getProvidedDependency(CategoryDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

    /**
     * @return \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToCategoryFacadeInterface
     */
    public function getCategoryFacade(): CategoryDiscountConnectorToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(CategoryDiscountConnectorDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CategoryDiscountConnector\Dependency\Facade\CategoryDiscountConnectorToProductCategoryFacadeInterface
     */
    public function getProductCategoryFacade(): CategoryDiscountConnectorToProductCategoryFacadeInterface
    {
        return $this->getProvidedDependency(CategoryDiscountConnectorDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }
}
