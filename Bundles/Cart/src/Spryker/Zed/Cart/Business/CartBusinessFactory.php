<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business;

use Spryker\Zed\Cart\Business\StorageProvider\InMemoryProvider;
use Spryker\Zed\Cart\Business\Operator\CouponCodeClearOperator;
use Spryker\Zed\Cart\Business\Operator\CouponCodeRemoveOperator;
use Spryker\Zed\Cart\Business\Operator\CouponCodeAddOperator;
use Spryker\Zed\Cart\Business\Operator\DecreaseOperator;
use Spryker\Zed\Cart\Business\Operator\RemoveOperator;
use Spryker\Zed\Cart\Business\Operator\IncreaseOperator;
use Spryker\Zed\Cart\Business\Operator\AddOperator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Cart\Business\Operator\OperatorInterface;
use Spryker\Zed\Cart\CartDependencyProvider;

/**
 * @method \Spryker\Zed\Cart\CartConfig getConfig()
 */
class CartBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Cart\Business\Operator\OperatorInterface
     */
    public function createAddOperator()
    {
        return $this->configureCartOperator(
            new AddOperator(
                $this->createStorageProvider(),
                $this->getCartCalculator(),
                $this->getItemGrouper()
                //@todo messenger
            )
        );
    }

    /**
     * @return \Spryker\Zed\Cart\Business\Operator\OperatorInterface
     */
    public function createIncreaseOperator()
    {
        return $this->configureCartOperator(
            new IncreaseOperator(
                $this->createStorageProvider(),
                $this->getCartCalculator(),
                $this->getItemGrouper()
                //@todo messenger
            )
        );
    }

    /**
     * @return \Spryker\Zed\Cart\Business\Operator\OperatorInterface
     */
    public function createRemoveOperator()
    {
        return $this->configureCartOperator(
            new RemoveOperator(
                $this->createStorageProvider(),
                $this->getCartCalculator(),
                $this->getItemGrouper()
                //@todo messenger
            )
        );
    }

    /**
     * @return \Spryker\Zed\Cart\Business\Operator\OperatorInterface
     */
    public function createDecreaseOperator()
    {
        return $this->configureCartOperator(
            new DecreaseOperator(
                $this->createStorageProvider(),
                $this->getCartCalculator(),
                $this->getItemGrouper()
                //@todo messenger
            )
        );
    }

    /**
     * @return \Spryker\Zed\Cart\Business\Operator\OperatorInterface
     */
    public function createCouponCodeAddOperator()
    {
        return $this->configureCartOperator(
            new CouponCodeAddOperator(
                $this->createStorageProvider(),
                $this->getCartCalculator(),
                $this->getItemGrouper()
                //@todo messenger
            )
        );
    }

    /**
     * @return \Spryker\Zed\Cart\Business\Operator\OperatorInterface
     */
    public function createCouponCodeRemoveOperator()
    {
        return $this->configureCartOperator(
            new CouponCodeRemoveOperator(
                $this->createStorageProvider(),
                $this->getCartCalculator(),
                $this->getItemGrouper()
                //@todo messenger
            )
        );
    }

    /**
     * @return \Spryker\Zed\Cart\Business\Operator\OperatorInterface
     */
    public function createCouponCodeClearOperator()
    {
        return $this->configureCartOperator(
            new CouponCodeClearOperator(
                $this->createStorageProvider(),
                $this->getCartCalculator(),
                $this->getItemGrouper()
                //@todo messenger
            )
        );
    }

    /**
     * @return \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface
     */
    protected function createStorageProvider()
    {
        return new InMemoryProvider();
    }

    /**
     * @return \Spryker\Zed\Cart\Dependency\Facade\CartToItemGrouperInterface
     */
    public function getItemGrouper()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_ITEM_GROUPER);
    }

    /**
     * @return \Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface
     */
    public function getCartCalculator()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @param \Spryker\Zed\Cart\Business\Operator\OperatorInterface $operator
     *
     * @return \Spryker\Zed\Cart\Business\Operator\OperatorInterface
     */
    protected function configureCartOperator(OperatorInterface $operator)
    {
        $bundleConfig = $this->getConfig();

        foreach ($bundleConfig->getCartItemPlugins() as $itemExpanderPlugin) {
            $operator->addItemExpanderPlugin($itemExpanderPlugin);
        }

        return $operator;
    }

}
