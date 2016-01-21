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
use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToItemGrouperInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Cart\Business\Operator\OperatorInterface;
use Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use Spryker\Zed\Cart\CartConfig;
use Spryker\Zed\Cart\CartDependencyProvider;

/**
 * @method CartConfig getConfig()
 */
class CartBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return OperatorInterface
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
     * @return OperatorInterface
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
     * @return OperatorInterface
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
     * @return OperatorInterface
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
     * @return OperatorInterface
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
     * @return OperatorInterface
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
     * @return OperatorInterface
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
     * @return StorageProviderInterface
     */
    protected function createStorageProvider()
    {
        return new InMemoryProvider();
    }

    /**
     * @return CartToItemGrouperInterface
     */
    public function getItemGrouper()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_ITEM_GROUPER);
    }


    /**
     * @deprecated Use getCartCalculator() instead.
     *
     * @return CartToCalculationInterface
     */
    public function createCartCalculator()
    {
        trigger_error('Deprecated, use getCartCalculator() instead.', E_USER_DEPRECATED);

        return $this->getCartCalculator();
    }

    /**
     * @return CartToCalculationInterface
     */
    public function getCartCalculator()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @param OperatorInterface $operator
     *
     * @return OperatorInterface
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
