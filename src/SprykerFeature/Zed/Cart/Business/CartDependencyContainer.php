<?php

namespace SprykerFeature\Zed\Cart\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\Cart2Business;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use Pyz\Zed\Calculation\Business\CalculationFacade;
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
    public function createCartCalculator()
    {
        return $this->getLocator()->calculation()->facade();
    }
}
