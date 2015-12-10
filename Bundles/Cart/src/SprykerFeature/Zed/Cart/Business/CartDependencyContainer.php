<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business;

use SprykerFeature\Zed\Cart\Business\StorageProvider\InMemoryProvider;
use SprykerFeature\Zed\Cart\Business\Operator\CouponCodeClearOperator;
use SprykerFeature\Zed\Cart\Business\Operator\CouponCodeRemoveOperator;
use SprykerFeature\Zed\Cart\Business\Operator\CouponCodeAddOperator;
use SprykerFeature\Zed\Cart\Business\Operator\DecreaseOperator;
use SprykerFeature\Zed\Cart\Business\Operator\RemoveOperator;
use SprykerFeature\Zed\Cart\Business\Operator\IncreaseOperator;
use SprykerFeature\Zed\Cart\Business\Operator\AddOperator;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\Cart\Business\Operator\OperatorInterface;
use SprykerFeature\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use SprykerFeature\Zed\Cart\CartConfig;
use SprykerFeature\Zed\Cart\CartDependencyProvider;
use SprykerFeature\Zed\ItemGrouper\Business\ItemGrouperFacade;

/**
 * @method CartConfig getConfig()
 */
class CartDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return OperatorInterface
     */
    public function createAddOperator()
    {
        return $this->configureCartOperator(
            new AddOperator(
                $this->createStorageProvider(),
                $this->createCartCalculator(),
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
                $this->createCartCalculator(),
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
                $this->createCartCalculator(),
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
                $this->createCartCalculator(),
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
                $this->createCartCalculator(),
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
                $this->createCartCalculator(),
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
                $this->createCartCalculator(),
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
     * @return ItemGrouperFacade
     */
    public function getItemGrouper()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_ITEM_GROUPER);
    }

    /**
     * @return CalculationFacade
     */
    public function createCartCalculator()
    {
        return $this->getProvidedDependency(CartDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @param OperatorInterface $operator
     *
     * @return OperatorInterface
     */
    private function configureCartOperator(OperatorInterface $operator)
    {
        $bundleConfig = $this->getConfig();

        foreach ($bundleConfig->getCartItemPlugins() as $itemExpanderPlugin) {
            $operator->addItemExpanderPlugin($itemExpanderPlugin);
        }

        return $operator;
    }

}
