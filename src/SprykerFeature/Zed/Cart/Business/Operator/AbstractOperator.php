<?php

namespace SprykerFeature\Zed\Cart\Business\Operator;

use SprykerFeature\Shared\Cart\Transfer\ItemInterface;
use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Shared\Cart\Transfer\CartChangeInterface;
use SprykerFeature\Shared\Cart\Transfer\CartInterface;
use Psr\Log\LoggerInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;
use SprykerFeature\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;

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
     * @var ItemExpanderPluginInterface[]
     */
    private $itemExpanderPlugins = [];

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
        $changedItems = $this->expandChangedItems($cartChange->getChangedItems());
        $cart = $this->changeCart($cartChange->getCart(), $changedItems);

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

    /**
     * @param ItemCollectionInterface|ItemInterface[] $changedItems
     *
     * @return ItemCollectionInterface|ItemInterface[]
     */
    protected function expandChangedItems(ItemCollectionInterface $changedItems)
    {
        foreach ($this->itemExpanderPlugins as $itemExpander) {
            $changedItems = $itemExpander->expandItems($changedItems);
        }

        return $changedItems;
    }

    /**
     * @param ItemExpanderPluginInterface $itemExpander
     *
     */
    public function addItemExpanderPlugin(ItemExpanderPluginInterface $itemExpander)
    {
        $this->itemExpanderPlugins[] = $itemExpander;
    }
}
