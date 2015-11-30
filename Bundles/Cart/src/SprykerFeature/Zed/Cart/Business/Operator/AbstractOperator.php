<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Operator;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\GroupableContainerTransfer;
use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use Generated\Shared\Transfer\ChangeTransfer;
use Psr\Log\LoggerInterface;
use SprykerFeature\Zed\Cart\Business\Model\CalculableContainer;
use SprykerFeature\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerFeature\Zed\ItemGrouper\Business\ItemGrouperFacade;

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
    protected $cartCalculator;

    /**
     * @var ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins = [];

    /**
     * @var ItemGrouperFacade
     */
    protected $itemGrouperFacade;

    /**
     * @param StorageProviderInterface $storageProvider
     * @param CalculationFacade $cartCalculator
     * @param ItemGrouperFacade $itemGrouperFacade
     * @param LoggerInterface $messenger
     */
    public function __construct(
        StorageProviderInterface $storageProvider,
        CalculationFacade $cartCalculator,
        ItemGrouperFacade $itemGrouperFacade,
        LoggerInterface $messenger = null //@todo to be discussed
)
    {
        $this->storageProvider = $storageProvider;
        $this->messenger = $messenger;
        $this->cartCalculator = $cartCalculator;
        $this->itemGrouperFacade = $itemGrouperFacade;
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function executeOperation(ChangeTransfer $cartChange)
    {
        $changedItems = $this->expandChangedItems($cartChange);
        $cart = $this->changeCart($cartChange->getCart(), $changedItems);

        if ($this->messenger) {
            $this->messenger->info($this->createSuccessMessage());
        }

        $cart = $this->recalculate($cart);

        return $cart;
    }

    /**
     * @param CartTransfer $cart
     *
     * @return CartTransfer
     */
    private function recalculate(CartTransfer $cart)
    {
        $calculableCart = new CalculableContainer($cart);
        $cart = $this->cartCalculator->recalculate($calculableCart);

        return $cart->getCalculableObject();
    }

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return CartTransfer
     */
    abstract protected function changeCart(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @return string
     */
    abstract protected function createSuccessMessage();

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    protected function expandChangedItems(ChangeTransfer $change)
    {
        foreach ($this->itemExpanderPlugins as $itemExpander) {
            $change = $itemExpander->expandItems($change);
        }

        return $change;
    }

    /**
     * @param ItemExpanderPluginInterface $itemExpander
     */
    public function addItemExpanderPlugin(ItemExpanderPluginInterface $itemExpander)
    {
        $this->itemExpanderPlugins[] = $itemExpander;
    }

    /**
     * @param CartTransfer $cart
     *
     * @return CartTransfer
     */
    protected function getGroupedCartItems(CartTransfer $cart)
    {
        $groupAbleItems = new GroupableContainerTransfer();
        $groupAbleItems->setItems($cart->getItems());

        $groupedItems = $this->itemGrouperFacade->groupItemsByKey($groupAbleItems);

        $cart->setItems($groupedItems->getItems());

        return $cart;
    }

}
