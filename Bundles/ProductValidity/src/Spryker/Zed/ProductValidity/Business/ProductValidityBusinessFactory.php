<?php


namespace Spryker\Zed\ProductValidity\Business;


use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductValidity\Business\Validity\ProductValidityHydrator;
use Spryker\Zed\ProductValidity\Business\Validity\ProductValidityHydratorInterface;
use Spryker\Zed\ProductValidity\Business\ProductConcrete\ProductConcreteSwitcher;
use Spryker\Zed\ProductValidity\Business\Validity\ProductValidityUpdater;
use Spryker\Zed\ProductValidity\Business\Validity\ProductValidityUpdaterInterface;
use Spryker\Zed\ProductValidity\Dependency\ProductValidityToProductFacadeInterface;
use Spryker\Zed\ProductValidity\ProductValidityDependencyProvider;

/**
 * @method \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface getQueryContainer()
 */
class ProductValidityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductValidity\Business\ProductConcrete\ProductConcreteSwitcherInterface
     */
    public function createProductConcreteSwitcher()
    {
        return new ProductConcreteSwitcher(
            $this->getQueryContainer(),
            $this->getProductFacade()
        );
    }

    /**
     * @return ProductValidityHydratorInterface
     */
    public function createProductValidityHydrator()
    {
        return new ProductValidityHydrator(
            $this->getQueryContainer()
        );
    }

    /**
     * @return ProductValidityUpdaterInterface
     */
    public function createProductValidityUpdater()
    {
        return new ProductValidityUpdater(
            $this->getQueryContainer()
        );
    }

    /**
     * @return ProductValidityToProductFacadeInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductValidityDependencyProvider::FACADE_PRODUCT);
    }
}