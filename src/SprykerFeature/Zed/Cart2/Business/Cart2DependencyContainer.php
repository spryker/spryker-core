<?php

namespace SprykerFeature\Zed\Cart2\Business;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\Cart2Business;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use Pyz\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\Cart2\Business\Operator\OperatorInterface;
use SprykerFeature\Zed\Cart2\Business\StorageProvider\StorageProviderInterface;

/**
 * @method Factory|Cart2Business getFactory()
 */
class Cart2DependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return OperatorInterface
     */
    public function createAddOperator()
    {
        return $this->getFactory()
            ->createOperatorAddOperator(
                $this->createStorageProvider(),
                $this->createCartCalculator()
                //@todo messenger
            )
        ;
    }

    /**
     * @return OperatorInterface
     */
    public function createIncreaseOperator()
    {
        return $this->getFactory()
            ->createOperatorIncreaseOperator(
                $this->createStorageProvider(),
                $this->createCartCalculator()
                //@todo messenger
            )
        ;
    }

    /**
     * @return OperatorInterface
     */
    public function createRemoveOperator()
    {
        return $this->getFactory()
            ->createOperatorRemoveOperator(
                $this->createStorageProvider(),
                $this->createCartCalculator()
                //@todo messenger
            )
        ;
    }

    /**
     * @return OperatorInterface
     */
    public function createDecreaseOperator()
    {
        return $this->getFactory()
        ->createOperatorDecreaseOperator(
            $this->createStorageProvider(),
            $this->createCartCalculator()
            //@todo messenger
        );
    }

    /**
     * @return StorageProviderInterface
     */
    private function createStorageProvider()
    {
        return $this->getFactory()->createStorageProviderInMemoryProvider();
    }

    /**
     * @return CalculationFacade
     */
    private function createCartCalculator()
    {
        return $this->getLocator()->calculation()->facade();
    }
}
