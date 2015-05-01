<?php

namespace SprykerFeature\Zed\Cart2\Business\Operator;

use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Shared\Cart2\Transfer\CartChangeInterface;
use SprykerFeature\Shared\Cart2\Transfer\CartInterface;
use Psr\Log\LoggerInterface;
use SprykerFeature\Shared\Cart2\Transfer\ItemCollectionInterface;
use SprykerFeature\Zed\Cart2\Business\StorageProvider\StorageProviderInterface;

abstract class AbstractOperator implements OperatorInterface
{
    /**
     * @var StorageProviderInterface
     */
    protected $storageProvider;

    /**
     * @var LoggerInterface
     */
    protected $messenger;
    /**
     * @var CalculationFacade
     */
    private $cartCalculator;

    /**
     * @param StorageProviderInterface $storageProvider
     * @param CalculationFacade $cartCalculator
     * @param LoggerInterface $messenger
     */
    public function __construct(
        StorageProviderInterface $storageProvider,
        CalculationFacade $cartCalculator,
        LoggerInterface $messenger = null //@todo to be discussed
    ) {
        $this->storageProvider = $storageProvider;
        $this->messenger = $messenger;
        $this->cartCalculator = $cartCalculator;
    }

    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function executeOperation(CartChangeInterface $cartChange)
    {
        $cart = $this->changeCart($cartChange->getCart(), $cartChange->getChangedItems());
        if ($this->messenger) {
            $this->messenger->info($this->createSuccessMessage());
        }

        $cart = $this->recalculate($cart);

        return $cart;
    }

    /**
     * @param CartInterface $cart
     *
     * @return CartInterface
     */
    private function recalculate(CartInterface $cart)
    {
        $cart = $this->cartCalculator->recalculate($cart);

        return $cart;
    }

    /**
     * @param CartInterface $cart
     * @param ItemCollectionInterface $changedItems
     *
     * @return CartInterface
     */
    abstract protected function changeCart(CartInterface $cart, ItemCollectionInterface $changedItems);

    /**
     * @return string
     */
    abstract protected function createSuccessMessage();
}
