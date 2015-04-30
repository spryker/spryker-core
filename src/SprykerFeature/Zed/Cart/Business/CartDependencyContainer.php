<?php

namespace SprykerFeature\Zed\Cart\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CartBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\Cart\Business\Operator\OperatorInterface;
use SprykerFeature\Zed\Cart\Business\StorageProvider\StorageProviderInterface;

/**
 * @method Factory|CartBusiness getFactory()
 */
class CartDependencyContainer extends AbstractDependencyContainer
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
        return $this->getLocator()->calculation()->facade();
    }

    /**
     * @param OperatorInterface $operator
     *
     * @return OperatorInterface
     */
    private function configureCartOperator(OperatorInterface $operator)
    {
        $settings = $this->getSettings();

        foreach ($settings->getCartItemPlugins() as $itemExpanderPlugin) {
            $operator->addItemExpanderPlugin($itemExpanderPlugin);
        }

        return $operator;
    }

    /**
     * @return CartSettings
     */
    protected function getSettings()
    {
        $settings = $this->getFactory()->createCartSettings($this->getLocator());

        return $settings;
    }
}
