<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Business;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductCategoryBusiness;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToLocaleInterface;
use SprykerFeature\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductInterface;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;

/**
 * @method ProductCategoryBusiness getFactory()
 */
class ProductCategoryDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return ProductCategoryManagerInterface
     */
    public function createProductCategoryManager()
    {
        return $this->getFactory()->createProductCategoryManager(
            $this->createProductCategoryQueryContainer(),
            $this->createProductFacade(),
            $this->createCategoryFacade(),
            $this->getLocator()
        );
    }

    /**
     * @return ProductCategoryQueryContainerInterface
     */
    protected function createProductCategoryQueryContainer()
    {
        return $this->getLocator()->productCategory()->queryContainer();
    }

    /**
     * @return ProductCategoryToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @return ProductCategoryToProductInterface
     */
    protected function createProductFacade()
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @return ProductCategoryToCategoryInterface
     */
    protected function createCategoryFacade()
    {
        return $this->getLocator()->category()->facade();
    }
}
