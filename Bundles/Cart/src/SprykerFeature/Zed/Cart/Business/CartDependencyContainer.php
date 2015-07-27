<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CartBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\Cart\Business\Operator\OperatorInterface;
use SprykerFeature\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use SprykerFeature\Zed\Cart\CartConfig;
use SprykerFeature\Zed\Cart\CartDependencyProvider;
use SprykerFeature\Zed\Cart\Business\Model;
use SprykerFeature\Zed\ItemGrouper\Business\ItemGrouperFacade;

/**
 * @method CartBusiness getFactory()
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
            $this->getFactory()
                ->createOperatorAddOperator(
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
            $this->getFactory()
                ->createOperatorIncreaseOperator(
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
            $this->getFactory()
                ->createOperatorRemoveOperator(
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
            $this->getFactory()
                ->createOperatorDecreaseOperator(
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
        return $this->getFactory()->createStorageProviderInMemoryProvider();
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
