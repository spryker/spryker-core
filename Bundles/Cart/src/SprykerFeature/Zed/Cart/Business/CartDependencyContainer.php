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
                    $this->createCartCalculator()
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
                    $this->createCartCalculator()
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
                    $this->createCartCalculator()
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
                    $this->createCartCalculator()
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

        foreach ($bundleConfig->getItemExpanderPlugins() as $itemExpanderPlugin) {
            $operator->addItemExpanderPlugin($itemExpanderPlugin);
        }

        return $operator;
    }

    /**
     * @return Model\ItemGrouping\KeyBuilder
     */
    public function createCartGroupingKeyBuilder()
    {
        $bundleConfig = $this->getConfig();

        return $this->getFactory()->createModelItemGroupingKeyBuilder($bundleConfig->getKeyBuilderPlugins());
    }

}
